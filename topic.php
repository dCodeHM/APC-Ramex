<?php
session_start();
include("config/RAMeXSO.php");
include("config/functions.php");

$user_data = check_login($conn_soe);

$user_name = "User"; // Default value

if (isset($_SESSION['account_id'])) {
    $account_id = $_SESSION['account_id'];
    
    // Prepare the query to fetch user's name
    $query = "SELECT first_name, last_name FROM soe_assessment_db.account WHERE account_id = ?";
    $stmt = $conn_soe->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $user_name = $row['first_name'] . ' ' . $row['last_name'];
        }
        
        $stmt->close();
    } else {
        // Handle the case where the prepare statement fails
        error_log("Failed to prepare statement: " . $conn_soe->error);
    }
}

if (!isset($_SESSION['account_id'])) {
    // Redirect to the login page if the user is not logged in
    echo '<script>alert("User is not logged in, directing to login page.")</script>';
    echo "<script> window.location.assign('login.php'); </script>";
    exit();
}

$account_id = $_SESSION['account_id'];

// Display the user-specific information
$sql = "SELECT * FROM account WHERE account_id = $account_id";
$result = mysqli_query($conn_soe, $sql); // Replace with data from the database
if ($result) {
    $row = mysqli_fetch_array($result);
    $user_email = $row['user_email'];
    $pwd = $row['user_password'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $role = $row['role'];
    $program_name = $row['program_name'];
}

// Assuming $user_data contains information about the user's role
$user_role = $user_data['role'];

// Check the user's role and set the redirection URL accordingly
if ($user_role == 'Executive Director') {
    $redirect_url = 'index.php'; // Redirect admin users to admin homepage
} elseif ($user_role == 'Program Director') {
    $redirect_url = 'index.php'; // Redirect professor users to professor homepage
} elseif ($user_role == 'Professor') {
    $redirect_url = 'professoruser.php'; // Redirect professor users to professor homepage
} else {
    $redirect_url = 'unauthorized.php'; // Redirect other users to a default homepage
}

// Retrieve the course code from the URL parameter
$courseCode = isset($_GET['course_code']) ? $_GET['course_code'] : '';

// Optionally, you may sanitize and validate the course code here
// Set the course code as the header text
$courseFolderName = $courseCode;

// Retrieve the course code AND course_subject_id from the URL parameter
$courseCode = isset($_GET['course_code']) ? $_GET['course_code'] : '';
$course_subject_id = isset($_GET['course_subject_id']) ? $_GET['course_subject_id'] : 0; // Get course_subject_id

// Set the course code as the header text
$courseFolderName = $courseCode;

// Fetch parameters from URL
// $course_subject_id = isset($_GET['course_subject_id']) ? intval($_GET['course_subject_id']) : 0;
// $course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
// $syllabus_course_id = isset($_GET['syllabus_course_id']) ? intval($_GET['syllabus_course_id']) : 0;
// $acy_id = isset($_GET['acy_id']) ? intval($_GET['acy_id']) : 0;
// $term = isset($_GET['term']) ? intval($_GET['term']) : 0;

// Fetch account_id and program_name from account table
$account_query = "SELECT account_id, program_name FROM account WHERE account_id = ?";
$account_stmt = $conn_soe->prepare($account_query);
$account_stmt->bind_param("i", $account_id);
$account_stmt->execute();
$account_result = $account_stmt->get_result();
$account_data = $account_result->fetch_assoc();

if (!$account_data) {
    die("Error: Unable to fetch user data for account_id: $account_id");
}

$program_name = $account_data['program_name'];

// Fetch program_id from program_name
$program_query = "SELECT program_id FROM program_name WHERE program_name = ?";
$program_stmt = $conn_soe->prepare($program_query);
$program_stmt->bind_param("s", $program_name);
$program_stmt->execute();
$program_result = $program_stmt->get_result();
$program_data = $program_result->fetch_assoc();

if (!$program_data) {
    die("Error: Unable to fetch program_id for program_name: $program_name");
}

$program_id = $program_data['program_id'];

// Fetch user_id from account table
$user_query = "SELECT account_id FROM account WHERE account_id = ?";
$user_stmt = $conn_soe->prepare($user_query);
$user_stmt->bind_param("i", $account_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_data = $user_result->fetch_assoc();

if (!$user_data) {
    die("Error: Unable to fetch user_id for account_id: $account_id");
}

$user_id = $user_data['account_id'];

// Fetch parameters from URL
$course_subject_id = isset($_GET['course_subject_id']) ? intval($_GET['course_subject_id']) : 0;
$course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
$syllabus_course_id = isset($_GET['syllabus_course_id']) ? intval($_GET['syllabus_course_id']) : 0;
$acy_id = isset($_GET['acy_id']) ? intval($_GET['acy_id']) : 0;
$term = isset($_GET['term']) ? intval($_GET['term']) : 0;

// Fetch course data from the course table
$course_query = "SELECT * FROM course 
                 WHERE course_code = ? 
                 AND syllabus_course_id = ? 
                 AND user_id = ? 
                 AND acy_id = ? 
                 AND term = ?";
$course_stmt = $conn_soe->prepare($course_query);
$course_stmt->bind_param("siiii", $course_code, $syllabus_course_id, $account_id, $acy_id, $term);
$course_stmt->execute();
$course_result = $course_stmt->get_result();
$course_data = $course_result->fetch_assoc();

$course_id = 0; // Default value
$program_id = 0;
$section = '';
$schedule = '';
$start_time = '';
$end_time = '';
$professor = '';
$room_no = '';
$target_rating = 0;
$submitted = 0;

if ($course_data) {
    $course_id = $course_data['course_id'];
    $program_id = $course_data['program_id'];
    $section = $course_data['section'];
    $schedule = $course_data['schedule'];
    $start_time = $course_data['start_time'];
    $end_time = $course_data['end_time'];
    $professor = $course_data['professor'];
    $room_no = $course_data['room_no'];
    $target_rating = $course_data['target_rating'];
    $submitted = $course_data['submitted'];
}

// Now you can use these variables in your HTML or for further processing

// Fetch activities for this course
// $activity_query = "SELECT activity_id, activity_name FROM activity WHERE course_id = ?";
// $activity_stmt = $conn_soe->prepare($activity_query);
// $activity_stmt->bind_param("i", $course_id);
// $activity_stmt->execute();
// $activity_result = $activity_stmt->get_result();

// Fetch activities for this course
// $activity_query = "SELECT activity_id, activity_name FROM activity WHERE course_id = ?";
// $activity_stmt = $conn_soe->prepare($activity_query);
// $activity_stmt->bind_param("i", $course_id);
// $activity_stmt->execute();
// $activity_result = $activity_stmt->get_result();

// Fetch activities for this course with activity_type 1
$activity_query = "SELECT activity_id, activity_name FROM activity WHERE course_id = ? AND activity_type = 1";
$activity_stmt = $conn_soe->prepare($activity_query);
$activity_stmt->bind_param("i", $course_id);
$activity_stmt->execute();
$activity_result = $activity_stmt->get_result();

// Fetch all numbered activities for this course
$numbered_activity_query = "SELECT na.* FROM numbered_activity na
                            JOIN activity a ON na.activity_id = a.activity_id
                            WHERE a.course_id = ?";
$numbered_activity_stmt = $conn_soe->prepare($numbered_activity_query);
$numbered_activity_stmt->bind_param("i", $course_id);
$numbered_activity_stmt->execute();
$numbered_activity_result = $numbered_activity_stmt->get_result();

$numbered_activities = [];
while ($row = $numbered_activity_result->fetch_assoc()) {
    $numbered_activities[$row['activity_id']][] = $row;
}


// nandito ako ngayon

// Fetch numbered_activity
// $activity_query = "SELECT * FROM numbered_activity WHERE numbered_id = ?";
// $activity_stmt = $conn_soe->prepare($activity_query);
// $activity_stmt->bind_param("i", $course_id);
// $activity_stmt->execute();
// $activity_result = $activity_stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="APC AcademX">
    <title>APC AcademX | Exam Maker</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/homepage.css">
    <link rel="stylesheet" href="./css/helpbutton.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/adminstyle.css">
    <link rel="stylesheet" href="./css/topicstyle.css">
    <link rel="shortcut icon" type="x-icon" href="./img/icon.png">
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    <script src="https://kit.fontawesome.com/e85940e9f2.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./myexams.js"></script>
</head>

<body>
        <h1>Course Topics for <?php echo htmlspecialchars($course_code); ?></h1>
        <h2>Activities and Numbered Activities</h2>


    <!--OTHER CODE -->
    <navigation class="navbar">
        <ul class="right-header">
            <li class="logo">
                <a href="<?php echo $redirect_url; ?>"><img id="logo" src="img/APC AcademX Logo.png"></a>
            </li>
        </ul>

        <ul class="left-header">
<li class="username">
    <h3><?php echo htmlspecialchars($user_name); ?></h3>
</li>
            <li class="notification">
                <a href="#" id="toggleNotif"><img id="notification" src="img/notification.png"></a>
                <ul class="notif-drop dropdown" id="notif-drop" style="display: none;">
                    <h3>Notifications</h3>
                    <hr>
                    <div class="notif-list">
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>
                        <!-- Repeat similar blocks for other notifications -->
                    </div>
                </ul>
            </li>
            <li class="user">
                <a href="#" id="toggleUser"><img id="profile" src="img/profile.png"></a>
                <ul class="user-drop dropdown" id="user-drop" style="display: none;">
                    <h3>Admin</h3>
                    <p>School Role</p>
                    <a href="adminusersettings.php" class="settings"><span>Settings</span></a>
                    <a href="logout.php" class="logout"><span>Logout</span></a>
                </ul>
            </li>
        </ul>

        <div class="sidebar">
            <div class="back_button">
                <a href="myexams.php">
                    <img src="img/back.png">
                </a>
            </div>
            <div class="help_buttonte">
                <img src="img/help.png" alt="Help Icon">
            </div>
        </div>
    </navigation>

    <div>
        <!-- Header + w/ TailwindCSS -->
        <div class="ml-[50px] p-10">
        <div class="mt-[70px] flex gap-4 justify-between items-center mb-10">
            <div>
                <p class="text-4xl mb-2">Course Folder Name</p>
                <h1 class="text-7xl font-medium"><?php echo htmlspecialchars($course_code); ?></h1>
            </div>
            <button class="addbutt" onclick="return showPopup()">
    <i class="fa-solid fa-circle-plus"></i>
</button>
        </div>
            <!-- Search bar -->
            <section class="flex gap-4 items-center mb-6">
                <div class="outline outline-1 outline-zinc-200 rounded-lg w-full">
                    <input type="text" class="border-transparent p-4 w-full" id="topicSearch" placeholder="Search here...">
                </div>
            </section>

            <div class="popup-hidden">
                <div class="popup_bg">
                    <div class="Add_popup">
                        <!-- Inside the form -->
                        <form action="topicfolder.php" method="post" id="createExamForm">
                            <input type="hidden" name="course_subject_id" value="<?php echo $course_subject_id ?>" readonly>
                            <input type="hidden" name="account_id" value="<?php echo $account_id ?>" readonly>
                            <input type="hidden" name="course_code" value="<?php echo $_GET['course_code']; ?>" readonly>

                            <div style="display: flex; align-items: center">
                                <img src="img/folder.png">
                                <p class="heading text-4xl"> Create an Exam</p>
                            </div>

                            <!-- <div class="inputcolumn">
                                <div>
                                    <label class="label" for="course_topics">Course Topic</label>
                                    <input class="input" type="text" name="course_topics" placeholder="Topic Name..." required><br />
                                </div>
                            </div> -->

<!-- HTML part -->
<div class="mb-6">
<label class="label font-bold text-2xl text-black" for="course_topics">Exams available</label>

    <?php
// if ($activity_result->num_rows > 0) {
//     echo "<select class='w-full p-2 rounded-lg border border-gray-800 shadow' id='course_topics' name='course_topics' onchange='showNumberedActivity(this.value)'>";
//     echo "<option value='' disabled selected>Select an activity</option>";
//     while ($activity = $activity_result->fetch_assoc()) {
//         echo "<option value='" . htmlspecialchars($activity['activity_id']) . "'>";
//         echo htmlspecialchars($activity['activity_name']);
//         echo "</option>";
//     }
//     echo "</select>";
// } else {
//     echo "<p>No activities found for this course.</p>";
//     echo "<select id='course_topics' style='display: none;'></select>"; // Hidden select for JS check
// }

if ($activity_result->num_rows > 0) {
    echo "<select class='w-full p-4 text-2xl rounded-lg border border-gray-800 shadow-lg' id='course_topics' name='course_topics' onchange='showNumberedActivity(this.value)'>";
    echo "<option value='' disabled selected>Select an activity</option>";
    while ($activity = $activity_result->fetch_assoc()) {
        echo "<option value='" . htmlspecialchars($activity['activity_id']) . "'>";
        echo htmlspecialchars($activity['activity_name']);
        echo "</option>";
    }
    echo "</select>";
} else {
    echo "<p class='text-2xl text-black'>No activities found for this course.</p>";
    echo "<select id='course_topics' style='display: none;'></select>"; // Hidden select for JS check
}
?>
</div>

<div id="numbered_activity_fields" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" style="display: none;">
    <!-- This div will be populated with the numbered activity fields -->
</div>

<script>
function showNumberedActivity(activityId) {
    const numberedActivityFields = document.getElementById('numbered_activity_fields');
    numberedActivityFields.innerHTML = ''; // Clear previous fields

    const numberedActivities = <?php echo json_encode($numbered_activities); ?>;

    if (numberedActivities[activityId]) {
        numberedActivityFields.style.display = 'grid';
        numberedActivities[activityId].forEach((item, index) => {
            const itemNames = item.item_name.split('~');
            const totalPoints = item.total_points.split('~');
            const cloIdRanges = item.clo_id_range.split('~');

            for (let i = 0; i < itemNames.length; i++) {
                // Only create a fieldset if there's actual data
                if (itemNames[i].trim() !== '' || totalPoints[i].trim() !== '' || cloIdRanges[i].trim() !== '') {
                    const fieldSet = document.createElement('fieldset');
                    fieldSet.className = 'p-4 border border-gray-300 rounded shadow-sm hover:shadow-md transition-shadow duration-300';

                    fieldSet.innerHTML = `
                        <legend class="font-bold text-lg mb-2">Item ${i + 1}</legend>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Item Name</label>
                            <input type="text" name="item_name[]" value="${itemNames[i]}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            
                            <label class="block text-sm font-medium text-gray-700">Total Points</label>
                            <input type="text" name="total_points[]" value="${totalPoints[i]}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            
                            <label class="block text-sm font-medium text-gray-700">CLO ID Range</label>
                            <input type="text" name="clo_id_range[]" value="${cloIdRanges[i]}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    `;

                    numberedActivityFields.appendChild(fieldSet);
                }
            }
        });

        // Adjust the grid based on the number of items
        const itemCount = numberedActivityFields.children.length;
        if (itemCount <= 4) {
            numberedActivityFields.className = `grid grid-cols-${itemCount} gap-4`;
        } else {
            numberedActivityFields.className = 'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4';
        }
    } else {
        numberedActivityFields.style.display = 'none';
    }
}
</script>

<div class="inputcolumn" style="display: none;">
    <p class="heading">Generate Questions</p>
    <div style="display: flex; align-items: center; justify-items:center">
        <div>
            <label class="label" for="easy_questions">Easy</label>
            <input class="difficultyinput" type="number" value="0" name="easy_questions" required min="0">
        </div>
        <div>
            <label class="label" for="normal_questions">Moderate</label>
            <input class="difficultyinput" type="number" value="0" name="normal_questions" required min="0">
        </div>
        <div>
            <label class="label" for="hard_questions">Hard</label>
            <input class="difficultyinput" type="number" value="0" name="hard_questions" required min="0">
        </div>
    </div>
</div>


                            <div>
                                <div class="actionbuttons">
                                    <button class="cancel" type="button" onclick="goBack()" style="margin-right: 10px;">Cancel</button>
                                    <button class="create" type="submit" name="create_exam">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--boxes-->
            <?php
            // Retrieve course_subject_id from URL parameters
            $course_subject_id = isset($_GET['course_subject_id']) ? $_GET['course_subject_id'] : 0;

          // Use course_subject_id in the SQL query
          $query = "SELECT pct.*, 
          IFNULL(pct.course_topics, 'Unnamed Activity') as activity_name
          FROM prof_course_topic pct
          WHERE pct.account_id = ? AND pct.course_subject_id = ?";

try {
$stmt = $mysqli_ramex->prepare($query);
if (!$stmt) {
throw new Exception("Prepare failed: " . $mysqli_ramex->error);
}

$stmt->bind_param("ii", $account_id, $course_subject_id);
if (!$stmt->execute()) {
throw new Exception("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows === 0) { ?>
<p class="header">You have no topic folders.</p>
<?php } else { ?>
<div class="flex flex-col gap-4">
<?php while ($row = $result->fetch_assoc()) : 
    $course_topic_id = $row['course_topic_id'];
    $activity_id = $row['course_topics'];
    
    // Fetch numbered activity information
    $numbered_activity_query = "SELECT * FROM numbered_activity WHERE activity_id = ?";
    $numbered_activity_stmt = $conn_soe->prepare($numbered_activity_query);
    $numbered_activity_stmt->bind_param("i", $activity_id);
    $numbered_activity_stmt->execute();
    $numbered_activity_result = $numbered_activity_stmt->get_result();
    $numbered_activity_data = $numbered_activity_result->fetch_assoc();
    
    // Filter out empty items
    $filtered_data = array();
    if ($numbered_activity_data) {
        $item_names = explode('~', $numbered_activity_data['item_name']);
        $total_points = explode('~', $numbered_activity_data['total_points']);
        $clo_id_ranges = explode('~', $numbered_activity_data['clo_id_range']);
        
        for ($i = 0; $i < count($item_names); $i++) {
            if (trim($item_names[$i]) !== '' || trim($total_points[$i]) !== '' || trim($clo_id_ranges[$i]) !== '') {
                $filtered_data[] = array(
                    'item_name' => $item_names[$i],
                    'total_points' => $total_points[$i],
                    'clo_id_range' => $clo_id_ranges[$i]
                );
            }
        }
    }
    
    // Encode the filtered data as JSON
    $numbered_activity_info = json_encode($filtered_data);
?>
      <div class="w-full bg-white hover:bg-zinc-50 transition-all duration-300 ease-in-out outline outline-zinc-200 outline-1 hover:outline-zinc-300 flex justify-between rounded-lg p-6 shadow-lg shadow-zinc-400/50 hover:shadow-xl hover:shadow-zinc-400/60" id="CourseNameBox">
                            <!-- Topics -->
                            <?php
                            // Get the exam_id using the course_topic_id
                            $course_topic_id = $row['course_topic_id'];
                            $exam_id_query = "SELECT exam_id FROM exam WHERE course_topic_id = $course_topic_id LIMIT 1";
                            $exam_id_result = $mysqli_ramex->query($exam_id_query);
                            $exam_id = 0;
                            if ($exam_id_result->num_rows > 0) {
                                $exam_id_row = $exam_id_result->fetch_assoc();
                                $exam_id = $exam_id_row['exam_id'];
                            }
                            ?>
<a href="examcreator.php?course_topic_id=<?php echo $row['course_topic_id']; ?>&course_code=<?php echo urlencode($courseCode); ?>&exam_id=<?php echo $exam_id; ?>">
                        <h2 class="font-semibold text-4xl text-zinc-700" id="topicBox">
                            <?php echo htmlspecialchars($row['activity_name']); ?>
                        </h2>
                        <p class="text-md text-gray-500">Date Created: <?php echo $row['date_created']; ?></p>
                    </a>
                    <select class="bg-transparent mb-2" onchange="handleAction(this)">
            <option value="">Select Action</option>
            <option value="info|<?php echo $course_topic_id; ?>" data-info='<?php echo htmlspecialchars($numbered_activity_info, ENT_QUOTES, 'UTF-8'); ?>'>Information</option>
            <option value="delete|<?php echo $row['course_topic_id']; ?>|<?php echo $course_subject_id; ?>|<?php echo urlencode($courseCode); ?>">Delete</option>
            <?php if (!empty($filtered_data)): ?>
            <?php endif; ?>
        </select>
                        </div>
                        <?php endwhile; ?>
        </div>
    <?php }
    $stmt->close();
} catch (Exception $e) {
    echo "An error occurred: " . htmlspecialchars($e->getMessage());
}
?>
        </div>
    </div>



    <script>

        
        function goBack() {
            const popup = document.querySelector(".popup_bg");
            popup.classList.add("popup-hidden");
        }

        function showPopup() {
            // Select the popup element
            const popup = document.querySelector(".popup-hidden");
            // Remove the "hidden" class to display the popup
            popup.classList.remove("popup-hidden");
            // Prevent default link behavior
            return false;
        }

        function showEditPopup(course_topic_id, update) {
            const popup = document.querySelector(".popup-hidden");
            popup.classList.remove("popup-hidden");
            document.getElementById("action").value = "edit";
            document.querySelector("form").action = `topicfolder.php?edit=${course_topic_id}`;
            // Optionally, you can pre-fill form fields here
            // Set the value of the update variable based on the update parameter
            if (update) {
                document.getElementById("update").value = "true";
            }
        }

        

        function handleAction(select) {
    if (select.value.startsWith('delete')) {
        const deleteData = select.value.split('|');
        const courseTopicId = deleteData[1];
        const courseSubjectId = deleteData[2];
        const courseCode = deleteData[3];

        if (confirm('Are you sure you want to delete this topic folder?')) {
            fetch(`http://localhost:8000/topicfolder.php?delete=${courseTopicId}&course_subject_id=${courseSubjectId}&course_code=${encodeURIComponent(courseCode)}`, {
                    method: 'GET'
                })
                .then(response => {
                    if (response.ok) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    } else {
                        throw new Error('Error deleting topic folder');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the topic folder.');
                });
        }
    } else if (select.value.startsWith('edit')) {
        const editUrl = select.value;
        const urlParams = new URLSearchParams(editUrl);
        const courseTopicId = urlParams.get('edit');
        const updateParam = urlParams.get('update');
        // Set $update variable based on the updateParam
        const update = updateParam === 'true';
        showEditPopup(courseTopicId, update);
    } else if (select.value.startsWith('info')) {
        const infoData = JSON.parse(select.options[select.selectedIndex].getAttribute('data-info'));
        showInfoPopup(infoData);
    }
}

function showInfoPopup(data) {
    const popup = document.createElement('div');
    popup.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center';
    
    let itemsHtml = '';
    data.forEach((item, index) => {
        itemsHtml += `
            <div class="mb-4">
                <h3 class="font-bold text-black text-4xl text-yellow-400">Item ${index + 1}</h3>
                ${item.item_name ? `<p class = "text-gray-700 text-2xl font-semibold">Item Name: ${item.item_name}</p>` : ''}
                ${item.total_points ? `<p class = "text-gray-700 text-2xl font-semibold">Total Points: ${item.total_points}</p>` : ''}
                ${item.clo_id_range ? `<p class = "text-gray-700 text-2xl font-semibold">CLO ID Range: ${item.clo_id_range}</p>` : ''}
            </div>
        `;
    });
    
    popup.innerHTML = `
        <div class="bg-white p-5 rounded-lg shadow-xl max-w-2xl">
            <h2 class="text-4xl font-bold mb-4 text-blue-800">Numbered Exam Information</h2>
            ${itemsHtml}
            <button class="bg-red-400 hover:bg-red-600 text-white text-2xl font-bold py-2 px-4 rounded" onclick="this.closest('.fixed').remove()">
                Close
            </button>
        </div>
    `;
    
    document.body.appendChild(popup);
}

        document.getElementById('topicSearch').addEventListener('input', function() {
            const searchQuery = this.value.toLowerCase().trim();
            const courseBoxes = document.querySelectorAll('#CourseNameBox'); // Select all course boxes
            courseBoxes.forEach(courseBox => {
                const courseName = courseBox.querySelector('#topicBox').textContent.toLowerCase();
                // Check if the course name contains the search query
                if (courseName.includes(searchQuery)) {
                    courseBox.style.display = 'flex'; // Show the course box
                } else {
                    courseBox.style.display = 'none'; // Hide the course box
                }
            });
        });

        // Function to check if activities exist and show popup if not
function checkActivitiesAndShowPopup() {
    const activitySelect = document.getElementById('course_topics');
    if (!activitySelect || activitySelect.options.length <= 1) {
        // No activities found (only the default "Select an activity" option)
        showNoActivitiesPopup();
        return false;
    }
    return true;
}

// Function to show the "No Activities" popup
function showNoActivitiesPopup() {
    const popup = document.createElement('div');
    popup.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center';
    popup.innerHTML = `
        <div class="bg-white p-5 rounded-lg shadow-xl">
            <h2 class="text-4xl font-bold mb-4">Cannot Create Exam</h2>
            <p class="mb-4 text-2xl">There are no activities available for this course. Please create a numbered exam before creating an exam.</p>
            <button class="bg-blue-500 hover:bg-blue-700 text-white text-2xl font-bold py-2 px-4 rounded" onclick="this.closest('.fixed').remove()">
                Close
            </button>
        </div>
    `;
    document.body.appendChild(popup);
}

// Modify the existing showPopup function
function showPopup() {
    if (checkActivitiesAndShowPopup()) {
        const popup = document.querySelector(".popup-hidden");
        popup.classList.remove("popup-hidden");
    }
    return false;
}
    </script>
</body>

</html>