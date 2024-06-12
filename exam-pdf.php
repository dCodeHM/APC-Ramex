<?php
include("exam-pdf-server.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$combined_result = array();

while ($question = $questions_result->fetch_assoc()) {
    $combined_result[] = array(
        'type' => 'question',
        'data' => $question
    );
}

usort($combined_result, function ($a, $b) {
    return strtotime($a['data']['date_created']) - strtotime($b['data']['date_created']);
});

$totalQuestions = count($combined_result);
$questionsPerPage = 30;
$totalPages = ceil($totalQuestions / $questionsPerPage);

$totalQuestionsWithChoices = count($combined_result);
$questionsPerPageWithChoices = 30;
$totalPagesWithChoices = ceil($totalQuestionsWithChoices / $questionsPerPageWithChoices);

$totalAnswerKeys = count($combined_result);
$answerKeysPerPage = 30;
$totalAnswerKeyPages = ceil($totalAnswerKeys / $answerKeysPerPage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="APC AcademX">

    <title>APC AcademX | Welcome</title>
    <link rel="shortcut icon" type="x-icon" href="./img/icon.png">
    <link rel="stylesheet" href="./css/header.css?v=<?php echo time(); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/css" media="print">
        @page {
            size: auto;
            /* auto is the initial value */
            margin: 0mm;
            /* this affects the margin in the printer settings */
        }

        @media print {
            .pagebreak {
                page-break-before: always;
            }

            /* page-break-after works, as well */

            /* Always add a margin top */
        }
    </style>

    <script>
        // Print as PDF
        window.onload = function() {
            window.print();
        }
    </script>

</head>

<body>

    <!-- Exam Preview -->
    <div id="exam-preview" class="text-white w-full flex flex-col bg-zinc-400 gap-10">
        <!-- Answer Sheet -->
        <?php
        $totalQuestions = count($combined_result);
        $questionsPerPage = 50;
        $totalPages = ceil($totalQuestions / $questionsPerPage);

        for ($page = 1; $page <= $totalPages; $page++) {
            $startIndex = ($page - 1) * $questionsPerPage;
            $endIndex = min($startIndex + $questionsPerPage, $totalQuestions);
        ?>
            <div class="page py-8 px-20 bg-white text-2xl text-zinc-800 w-[210mm]">
                <div class="w-full flex items-center justify-between gap-4 text-xl font-normal text-zinc-800">
                    <!-- Get the params course_code in the URL -->
                    <p>
                        <?php
                        $course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
                        echo $course_code;
                        ?>
                    </p>

                    <img src="img/APC AcademX Logo.png" alt="APC AcademX Logo" class="max-w-[100px]">

                    <h4 class="text-zinc-800">
                        <?php echo htmlspecialchars($exam['exam_name']); ?>
                    </h4>
                </div>
                <hr class="my-8" />

                <?php if ($page === 1) { ?>
                    <div class="w-full flex items-center h-[100px] border-black border-1 mb-6">
                        <div class="w-[80%] flex flex-col h-full">
                            <div class="h-full p-4 border-[1px] border-black">Name:</div>
                            <div class="flex h-full">
                                <div class="w-full h-full p-4 border-[1px] border-black">Section:</div>
                                <div class="w-full h-full p-4 border-[1px] border-black">Date:</div>
                            </div>
                        </div>
                        <div class="w-[20%] h-full p-4 border-[1px] border-black">
                            Score:
                        </div>
                    </div>
                    <p class="mb-6">
                        <!-- Exam Instructions -->
                        <?php echo htmlspecialchars($exam['exam_instruction']); ?>
                    </p>
                <?php } ?>

                <!-- Answer Sheet -->
                <div id="answer-sheet">
                    <div class="flex justify-between">
                        <?php
                        $questionsPerColumn = 25;
                        $columnsPerPage = 2;

                        for ($column = 1; $column <= $columnsPerPage; $column++) {
                            $columnStartIndex = $startIndex + ($column - 1) * $questionsPerColumn;
                            $columnEndIndex = min($columnStartIndex + $questionsPerColumn, $endIndex);
                        ?>
                            <div class="column w-1/2">
                                <?php for ($i = $columnStartIndex; $i < $columnEndIndex; $i++) {
                                    $item = $combined_result[$i];
                                    if ($item['type'] === 'question') {
                                        $question = $item['data'];
                                ?>
                                        <div class="question flex gap-4 items-center">
                                            <p class="font-semibold"><?php echo $i + 1; ?>.</p>
                                            <div class="choices-container flex gap-4">
                                                <?php
                                                $sql = "SELECT * FROM question_choices WHERE answer_id = ?";
                                                $stmt = $conn->prepare($sql);
                                                if (!$stmt) {
                                                    die("Error preparing statement: " . $conn->error);
                                                }

                                                $stmt->bind_param("i", $question['answer_id']);
                                                if (!$stmt->execute()) {
                                                    die("Error executing statement: " . $stmt->error);
                                                }

                                                $choices_result = $stmt->get_result();
                                                $choiceIndex = 0;

                                                while ($choice = $choices_result->fetch_assoc()) {
                                                    $choiceLetter = chr(65 + $choiceIndex);
                                                ?>
                                                    <div class="choice flex items-center">
                                                        <div class="w-6 h-6 rounded-full border-[1px] border-black flex items-center justify-center">
                                                            <span class="text-base font-semibold"><?php echo $choiceLetter; ?></span>
                                                        </div>
                                                    </div>
                                                <?php
                                                    $choiceIndex++;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Footer -->
                <hr class="mt-8" />
                <div class="w-full flex justify-center mt-4 text-lg">
                    <p>Page <?php echo $page; ?> of <?php echo $totalPages; ?></p>
                </div>
            </div>

            <?php if ($page < $totalPages) { ?>
                <div class="pagebreak"> </div>
            <?php } ?>
        <?php } ?>
        <div class="pagebreak"> </div>

        <!-- Questions and Choices -->
        <?php
        $totalQuestionsWithChoices = count($combined_result);
        $questionsPerPageWithChoices = 30;
        $totalPagesWithChoices = ceil($totalQuestionsWithChoices / $questionsPerPageWithChoices);

        $page = 1;
        $questionIndex = 0;

        while ($questionIndex < $totalQuestionsWithChoices) {
            $remainingQuestions = $totalQuestionsWithChoices - $questionIndex;
            $questionsOnPage = min($questionsPerPageWithChoices, $remainingQuestions);
        ?>
            <div class="page py-8 px-20 bg-white text-2xl text-zinc-800 w-[210mm]">
                <div class="w-full flex items-center justify-between gap-4 text-xl font-normal text-zinc-800">
                    <!-- Get the params course_code in the URL -->
                    <p>
                        <?php
                        $course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
                        echo $course_code;
                        ?>
                    </p>

                    <img src="img/APC AcademX Logo.png" alt="APC AcademX Logo" class="max-w-[100px]">

                    <h4 class="text-zinc-800">
                        <?php echo htmlspecialchars($exam['exam_name']); ?>
                    </h4>
                </div>
                <hr class="my-8" />

                <div class="flex justify-between">
                    <?php
                    $questionsPerColumnWithChoices = 15;
                    $columnsPerPageWithChoices = 2;
                    $columnIndex = 0;

                    while ($columnIndex < $columnsPerPageWithChoices && $questionIndex < $totalQuestionsWithChoices) {
                        $questionsInColumn = 0;
                        $imagesInColumn = 0;
                    ?>
                        <div class="column w-1/2">
                            <?php
                            while ($questionsInColumn < $questionsPerColumnWithChoices && $questionIndex < $totalQuestionsWithChoices) {
                                $item = $combined_result[$questionIndex];
                                if ($item['type'] === 'question') {
                                    $question = $item['data'];
                                    $hasQuestionImage = !empty($question['question_image']);
                                    $choiceImages = 0;

                                    // Count the number of choice images
                                    $sql = "SELECT COUNT(*) AS count FROM question_choices WHERE answer_id = ? AND answer_image IS NOT NULL";
                                    $stmt = $conn->prepare($sql);
                                    if (!$stmt) {
                                        die("Error preparing statement: " . $conn->error);
                                    }
                                    $stmt->bind_param("i", $question['answer_id']);
                                    if (!$stmt->execute()) {
                                        die("Error executing statement: " . $stmt->error);
                                    }
                                    $choices_result = $stmt->get_result();
                                    $row = $choices_result->fetch_assoc();
                                    $choiceImages = $row['count'];

                                    $totalImages = ($hasQuestionImage ? 1 : 0) + $choiceImages;

                                    if ($imagesInColumn + $totalImages <= 3) {
                                        $imagesInColumn += $totalImages;
                            ?>
                                        <div class="question mb-4">
                                            <!-- Show question image -->
                                            <?php if ($hasQuestionImage) : ?>
                                                <?php
                                                $imgData = base64_encode($question['question_image']);
                                                $src = 'data:image/jpeg;base64,' . $imgData;
                                                ?>
                                                <img src="<?php echo $src; ?>" alt="Question Image" class="max-w-xs max-h-xs mt-4">
                                            <?php endif; ?>
                                            <p class="font-semibold mb-2 mt-4"><?php echo $questionIndex + 1; ?>. <?php echo $question['question_text']; ?></p>
                                            <div class="choices-container">
                                                <?php
                                                $sql = "SELECT * FROM question_choices WHERE answer_id = ?";
                                                $stmt = $conn->prepare($sql);
                                                if (!$stmt) {
                                                    die("Error preparing statement: " . $conn->error);
                                                }

                                                $stmt->bind_param("i", $question['answer_id']);
                                                if (!$stmt->execute()) {
                                                    die("Error executing statement: " . $stmt->error);
                                                }

                                                $choices_result = $stmt->get_result();
                                                $choiceIndex = 0;

                                                while ($choice = $choices_result->fetch_assoc()) {
                                                    $choiceLetter = chr(65 + $choiceIndex);
                                                ?>
                                                    <!-- Show answer image -->
                                                    <?php if (!empty($choice['answer_image'])) : ?>
                                                        <?php
                                                        $imgData = base64_encode($choice['answer_image']);
                                                        $src = 'data:image/jpeg;base64,' . $imgData;
                                                        ?>
                                                        <img src="<?php echo $src; ?>" alt="Answer Image" class="max-w-xs max-h-xs mt-4">
                                                    <?php endif; ?>

                                                    <p class="mt-2"><?php echo $choiceLetter; ?>. <?php echo $choice['answer_text']; ?></p>
                                                <?php
                                                    $choiceIndex++;
                                                }
                                                ?>
                                            </div>
                                        </div>
                            <?php
                                        $questionsInColumn++;
                                        $questionIndex++;
                                    } else {
                                        break;
                                    }
                                }
                            }
                            ?>
                        </div>
                    <?php
                        $columnIndex++;
                    }
                    ?>
                </div>

                <!-- Footer -->
                <hr class="mt-8" />
                <div class="w-full flex justify-center mt-4 text-lg">
                    <p>Page <?php echo $page; ?></p>
                </div>
            </div>

            <?php if ($questionIndex < $totalQuestionsWithChoices) : ?>
                <div class="pagebreak"></div>
                <?php $page++; ?>
            <?php endif; ?>
        <?php
        }
        ?>


        <div class="pagebreak"> </div>

        <!-- Answer Keys -->
        <?php
        $totalAnswerKeys = count($combined_result);
        $questionsPerColumn = 10;
        $columnsPerAnswerKeyPage = 2;
        $questionsPerPage = $questionsPerColumn * $columnsPerAnswerKeyPage;
        $totalAnswerKeyPages = ceil($totalAnswerKeys / $questionsPerPage);

        $page = 1;
        $questionCount = 0;
        $columnIndex = 0;

        echo '<div class="page py-8 px-20 bg-white text-2xl text-zinc-800 w-[210mm]">';
        echo '<div class="w-full flex items-center justify-between gap-4 text-xl font-normal text-zinc-800">';
        echo '<p>';
        $course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
        echo $course_code;
        echo '</p>';
        echo '<img src="img/APC AcademX Logo.png" alt="APC AcademX Logo" class="max-w-[100px]">';
        echo '<h4 class="text-zinc-800">';
        echo htmlspecialchars($exam['exam_name']);
        echo '</h4>';
        echo '</div>';
        echo '<div class="w-full h-0.5 my-8 bg-black"></div>';
        echo '<div class="flex justify-between">';

        for ($i = 0; $i < $totalAnswerKeys; $i++) {
            $item = $combined_result[$i];
            if ($item['type'] === 'question') {
                $question = $item['data'];

                if ($questionCount % $questionsPerPage === 0 && $questionCount > 0) {
                    echo '</div>'; // Close the previous column
                    echo '</div>'; // Close the flex container
                    echo '<hr class="mt-8" />';
                    echo '<div class="w-full flex justify-center mt-4 text-lg">';
                    echo '<p>Answer Keys - Page ' . $page . ' of ' . $totalAnswerKeyPages . '</p>';
                    echo '</div>';
                    echo '</div>'; // Close the page
                    echo '<div class="pagebreak"> </div>'; // Add a page break

                    echo '<div class="page py-8 px-20 bg-white text-2xl text-zinc-800 w-[210mm]">';
                    echo '<div class="w-full flex items-center justify-between gap-4 text-xl font-normal text-zinc-800">';
                    echo '<p>' . $course_code . '</p>';
                    echo '<img src="img/APC AcademX Logo.png" alt="APC AcademX Logo" class="max-w-[100px]">';
                    echo '<h4 class="text-zinc-800">' . htmlspecialchars($exam['exam_name']) . '</h4>';
                    echo '</div>';
                    echo '<div class="w-full h-0.5 my-8 bg-black"></div>';
                    echo '<div class="flex justify-between">';

                    $page++;
                    $columnIndex = 0;
                }

                if ($questionCount % $questionsPerColumn === 0) {
                    if ($columnIndex > 0) {
                        echo '</div>'; // Close the previous column
                    }
                    echo '<div class="column w-1/2">'; // Start a new column
                    $columnIndex++;
                }

                echo '<div class="question mb-4">';
                echo '<p class="font-semibold mb-2 mt-4">' . ($i + 1) . '. ' . $question['question_text'] . '</p>';
                echo '<div class="choices-container">';

                $sql = "SELECT * FROM question_choices WHERE answer_id = ? AND is_correct = 1";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die("Error preparing statement: " . $conn->error);
                }

                $stmt->bind_param("i", $question['answer_id']);
                if (!$stmt->execute()) {
                    die("Error executing statement: " . $stmt->error);
                }

                $choices_result = $stmt->get_result();
                $choiceIndex = 0;

                while ($choice = $choices_result->fetch_assoc()) {
                    $choiceLetter = chr(65 + $choiceIndex);
                    echo '<p class="mb-1">' . $choiceLetter . '. ' . $choice['answer_text'] . '</p>';
                    $choiceIndex++;
                }

                echo '</div>';
                echo '</div>';

                $questionCount++;
            }
        }

        echo '</div>'; // Close the last column
        echo '</div>'; // Close the flex container
        echo '<hr class="mt-8" />';
        echo '<div class="w-full flex justify-center mt-4 text-lg">';
        echo '<p>Answer Keys - Page ' . $page . ' of ' . $totalAnswerKeyPages . '</p>';
        echo '</div>';
        echo '</div>'; // Close the page
        ?>
    </div>
</body>


</html>