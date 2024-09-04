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
// answer sheet ito lang yung may box 
$totalQuestions = count($combined_result);
$questionsPerPage = 100;
$totalPages = ceil($totalQuestions / $questionsPerPage);

// questions and choices
// $totalQuestionsWithChoices = count($combined_result);
// $questionsPerPageWithChoices = 30;
// $totalPagesWithChoices = ceil($totalQuestionsWithChoices / $questionsPerPageWithChoices);

// answer keys
// $totalAnswerKeys = count($combined_result);
// $answerKeysPerPage = 30;
// $totalAnswerKeyPages = ceil($totalAnswerKeys / $answerKeysPerPage);

// Update the displayImage function
// function displayImage($imageData, $alt, $maxWidth = 150, $maxHeight = 100) {
//     $imgData = base64_encode($imageData);
//     $src = 'data:image/jpeg;base64,' . $imgData;
//     return "<img src='{$src}' alt='{$alt}' style='max-width:{$maxWidth}px; max-height:{$maxHeight}px; width:auto; height:auto; object-fit:contain; display:inline-block; vertical-align:middle;'>";
// }


// header and footer

function renderHeader($course_code, $exam_name, $qr_code_path) {
    ?>
<div class="w-full flex items-center justify-between text-xl font-normal text-zinc-800">
    <!-- Course Code (Left) -->
    <div class="w-1/8">
        <p><?php echo htmlspecialchars($course_code); ?></p>
    </div>
    
    <!-- APC AcademX Logo (Center) -->
    <div class="w-1/4 flex justify-end">
        <img src="img/APC AcademX Logo.png" alt="APC AcademX Logo" class="max-w-[150px]">
    </div>
    
    <!-- QR Code and Exam Name (Right) -->
    <div class="w-1/2 flex justify-end">
        <?php
        if (!empty($qr_code_path) && file_exists($qr_code_path)) {
            echo "<div class='flex items-center bg-white p-4'>";
            // exam name
            echo "<span class='mr-4'>" . htmlspecialchars($exam_name) . "</span>";
            // qr code
            echo "<img src='" . htmlspecialchars($qr_code_path) . "' alt='Exam QR Code' class='w-20 h-20 object-contain'>";
            echo "</div>";
        } else {
            echo '<p class="text-red-500 text-sm">QR Code not available</p>';
        }
        ?>
    </div>
</div>


    <hr class="my-8" />
    <?php
}

function renderFooter($page, $totalPages) {
    ?>
    <div class="w-full flex justify-center mt-4 text-2xl">
        <p>Page <?php echo $page; ?> of <?php echo $totalPages; ?></p>
    </div>
    <?php
}


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
            size: A4;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 297mm;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
            position: relative;
        }
        .answer-sheet {
        position: relative;
        border: 1px solid white;
        margin-top: 20px;
        width: 170mm; /* Fixed width */
        height: 200mm; /* Fixed height - adjust as needed */
        overflow: hidden; /* Prevents content from spilling out */
        }
        .answer-sheet-content {
        padding: 10px;
        height: 100%;
    }
        .corner-square {
            position: absolute;
            width: 8px;
            height: 8px;
            background-color: black;
            border: 5px solid black;
            z-index: 10;
        }
        .top-left { top: 110px; left: 0px; }
        .top-right { top: 110px; right: 0px; }
        .bottom-left { bottom: 0px; left: 0px; }
        .bottom-right { bottom: 0px; right: 0px; }
    </style>

    <script>
        // Print as PDF
        window.onload = function() {
            window.print();
        }
    </script>

</head>

<body>
    <!-- Student Answer Sheet Preview -->
    <table id="exam-preview" class="text-white w-full flex flex-col bg-zinc-400 gap-10">
    <?php
        $course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
        $questionsPerColumn = 25;
        $columnsPerPage = 4;

        for ($page = 1; $page <= $totalPages; $page++) {
            $startIndex = ($page - 1) * $questionsPerPage;
            $endIndex = min($startIndex + $questionsPerPage, $totalQuestions);
        ?>
            <div class="page py-8 px-20 bg-white text-2xl text-zinc-800 w-[210mm]">
            <?php renderHeader($course_code, $exam['exam_name'], $exam['qr_code']); ?>
                <?php if ($page === 1) { ?>
                    <!-- <div class="w-full flex items-center h-[100px] border-black border-1 mb-6">
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
                    </div> -->
                    <p class="mb-6">
                        <!-- Exam Instructions -->
                        <?php echo htmlspecialchars($exam['exam_instruction']); ?>
                    </p>
                <?php } ?>

                <!-- Answer Sheet -->
                <div id="answer-sheet" class="answer-sheet">
    <!-- Corner Squares -->
    <div class="corner-square top-left"></div>
    <div class="corner-square top-right"></div>
    <div class="corner-square bottom-left"></div>
    <div class="corner-square bottom-right"></div>
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

    <div class="answer-sheet-content flex flex-wrap">
    <?php
    $questionsPerColumn = 25;
    $columnsPerPage = 4;
    $questionsOnThisPage = min($questionsPerPage, $endIndex - $startIndex);

    for ($column = 0; $column < $columnsPerPage; $column++) {
        $columnStartIndex = $startIndex + ($column * $questionsPerColumn);
        $columnEndIndex = min($columnStartIndex + $questionsPerColumn, $startIndex + $questionsOnThisPage);
        ?>
        <div class="column w-1/4 pr-4">
            <?php
            for ($i = $columnStartIndex; $i < $columnEndIndex; $i++) {
                if ($i >= $totalQuestions) break;
                $item = $combined_result[$i];
                if ($item['type'] === 'question') {
                    $question = $item['data'];
                    ?>
                    <div class="question flex gap-4 items-center mb-2">
                        <p class="font-semibold"><?php echo $i + 1; ?>.</p>
                        <div class="choices-container flex gap-4">
                            <?php
                            $sql = "SELECT * FROM question_choices WHERE answer_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $question['answer_id']);
                            $stmt->execute();
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
        <?php
    }
    ?>
</div>
</div>

                <?php renderFooter($page, $totalPages); ?>
            </div>

            <?php if ($page < $totalPages) { ?>
                <div class="pagebreak"></div>
            <?php } ?>
        <?php } ?>
</table>

    <script>
        // Print as PDF
        window.onload = function() {
            window.print();
        }
    </script>

<!-- Question and Choices Sheet -->
<div id="exam-preview" class="text-white w-full flex flex-col bg-zinc-400 gap-10">


    </div>
</body>
</html>