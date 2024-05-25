<?php
session_start();
include("config/db.php");
include("config/functions.php");

$user_data = check_login($conn);

if (!isset($_SESSION['account_id'])) {
    // Redirect to the login page if the user is not logged in
    echo '<script>alert("User is not logged in, directing to login page.")</script>';
    echo "<script> window.location.assign('login.php'); </script>";
    exit();
}

$account_id = $_SESSION['account_id'];

// Display the user-specific information
$sql = "SELECT * FROM account WHERE account_id = $account_id";
$result = mysqli_query($conn, $sql); // Replace with data from the database
if ($result) {
    $row = mysqli_fetch_array($result);
    $user_email = $row['user_email'];
    $pwd = $row['pwd'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $role = $row['role'];
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

// require('topicfolder.php');

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


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="APC AcademX">
    <title>APC AcademX | Exam Maker</title>
    <link rel="stylesheet" href="./css/topicstyle.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/adminstyle.css">
    <link rel="stylesheet" href="./css/emstyle.css">
    <link rel="stylesheet" href="./css/myexamstyle.css">
    <link rel="stylesheet" href="./css/topicrectangledesign.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/homepage.css">
    <script src="https://kit.fontawesome.com/e85940e9f2.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./myexams.js"></script>
</head>

<body>
    <!--OTHER CODE -->
    <navigation class="navbar">
        <ul class="right-header">
            <li class="logo">
                <a href="<?php echo $redirect_url; ?>"><img id="logo" src="img/logo.png"></a>
            </li>
        </ul>

        <ul class="left-header">
            <?php
            // Check if the session variable exists
            if (isset($_SESSION['user'])) {
                // Retrieve data from the session variable
                $userData = $_SESSION['user'];

                // // Access specific data from the session variable
                // $username = $userData['username'];
                // $email = $userData['email'];

                // Output the retrieved data in HTML text
                echo "<li class='username'><h3>$userData</h3></li>";
            } else {
                // Session variable does not exist or user is not logged in
                echo "<li class='username'><h3>$row[first_name] $row[last_name]</h3></li>";
            }
            ?>


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
                        </div>;
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                    </div>
                </ul>
            </li>

            <li class="user">
                <a href="#" id="toggleUser"><img id="profile" src="img/profile.png"></a>
                <ul class="user-d   rop dropdown" id="user-drop" style="display: none;">
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
            <div class="help_button">
                <img src="img/help.png">
            </div>
        </div>
    </navigation>


    <div>

        <!-- Header + w/ TailwindCSS -->
        <div class="ml-[50px] p-10">
            <div class="mt-[70px] flex gap-4 justify-between items-center mb-10">
                <div>
                    <p class="text-4xl mb-2">Course Folder Name</p>
                    <h1 class="text-7xl font-medium"><?php echo $courseFolderName; ?></h1>
                </div>

                    <button class="addbutt" onclick="showPopup()">
                        <i class="fa-solid fa-circle-plus"></i>
                    </button>
            </div>


            <!-- Search bar -->
            <section class="flex gap-4 items-center mb-6">
                <div class="outline outline-1 outline-zinc-200 rounded-lg w-full">
                    <input type="text" class="border-transparent p-4 w-full" placeholder="Search here...">
                </div>
                <button class="max-w-fit flex gap-4 items-center bg-[#293A82] py-4 px-6 rounded-xl text-white">
                    Search <i class="fa-solid fa-search"></i>
                </button>
            </section>

            <div class="popup-hidden">
    <div class="popup_bg"></div>
    <div class="Add_popup">
        <!-- Inside the form -->
        <form action="topicfolder.php" method="post" id="createExamForm">
            <input type="hidden" name="course_subject_id" value="<?php echo $course_subject_id ?>" readonly><br />
            <input type="hidden" name="account_id" value="<?php echo $account_id ?>" readonly><br />

            <div class="inputcolumn">
                <label class="label" for="course_topics">Course Topic</label>
                <input class="input" type="text" name="course_topics" placeholder="Your Course Topics" required><br />
            </div>
            <div class="inputcolumn">
                <label class="label" for="easy_questions">Easy</label>
                <input class="input" type="number" name="easy_questions" placeholder="How many Easy question/s?" required><br />
            </div>
            <div class="inputcolumn">
                <label class="label" for="normal_questions">Normal</label>
                <input class="input" type="number" name="normal_questions" placeholder="How many Normal question/s?" required><br />
            </div>
            <div class="inputcolumn">
                <label class="label" for="hard_questions">Hard</label>
                <input class="input" type="number" name="hard_questions" placeholder="How many Hard question/s?" required><br />
            </div>

            <button type="submit" name="create_exam">Create Exam</button>
            <!-- Cancel button -->
            <button type="button" onclick="goBack()">Cancel</button>
        </form>
    </div>
</div>

<script>
    function goBack() {
        // Extract parameters from the current URL
        var urlParams = new URLSearchParams(window.location.search);
        var courseSubjectId = urlParams.get('course_subject_id');
        var courseCode = urlParams.get('course_code');

        // Construct the new URL
        var url = 'http://localhost/topic.php';
        url += '?course_subject_id=' + courseSubjectId;
        url += '&course_code=' + courseCode;

        // Redirect to the new URL
        window.location.href = url;
    }
</script>




            <!--boxes-->
            <?php

            // Retrieve course_subject_id from URL parameters
            $course_subject_id = isset($_GET['course_subject_id']) ? $_GET['course_subject_id'] : 0;

            // Use course_subject_id in the SQL query
            $result = $mysqli->query("SELECT * from prof_course_topic WHERE account_id = $account_id AND course_subject_id = $course_subject_id") or die(mysqli_error($mysqli));

            if ($result->num_rows === 0) { ?>

                <p class="header">You have no topic folders.</p>

            <?php } else { ?>
                <div class="flex flex-col gap-4">
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <div class="w-full hover:bg-zinc-100 transition-all duration-300 ease-in-out outline outline-zinc-200 outline-1 flex justify-between rounded-lg p-6">
                            <!-- Topics -->
                            <a href="examcreator.php?course_topic_id=<?php echo $row['course_topic_id']; ?>&course_code=<?php echo urlencode($courseCode); ?>">
                                <h2 class="font-semibold text-4xl text-zinc-700">
                                    <?php echo $row['course_topics']; ?></h2>
                                <!-- Date Created -->
                                <p class="text-md text-gray-500">Date Created: <?php echo $row['date_created']; ?></p>
                            </a>
                            <select class="bg-transparent mb-2" onchange="handleAction(this)">
                                <option value="">Select Action</option>
                                <option value="topic.php?edit=<?php echo $row['course_topic_id']; ?>&update=true&course_subject_id=<?php echo $course_subject_id; ?>&course_code=<?php echo urlencode($courseCode); ?>">Edit</option>
                                <option value="topicfolder.php?delete=<?php echo $row['course_topic_id']; ?>">Delete</option>
                            </select>
                        </div>
                <?php endwhile;
                }
                ?>
                </div>
        </div>
    </div>
</body>

<script>
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
        // Set the value of the $update PHP variable based on the   update parameter
        <?php if ($update) : 
        ?>document.getElementById("update").value = "true";<?php endif; ?>
    }

    function handleAction(select) {
        if (select.value.startsWith('topicfolder.php?delete=')) {
            if (confirm('Are you sure you want to delete this topic folder?')) {
                window.location = select.value;
            }
        } else if (select.value.startsWith('topic.php?edit=')) {
            const editUrl = select.value;
            const urlParams = new URLSearchParams(editUrl);
            const courseTopicId = urlParams.get('edit');
            const updateParam = urlParams.get('update');
            // Set $update variable based on the updateParam
            const update = updateParam === 'true';
            showEditPopup(courseTopicId, update);
        }
    }

    function logFormData(event) {
        event.preventDefault(); // Prevent form submission for now
        const formData = new FormData(event.target);
        for (const [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        // After logging, you can submit the form if needed:
        // event.target.submit();
    }
</script>
<?php
?>

</html>