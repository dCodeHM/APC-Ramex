<?php
session_start();

include("config/db.php");
include("config/functions.php");

$user_data = check_login($conn);

$account_id = $_SESSION['account_id'];
$sql = "SELECT * FROM  account WHERE account_id = '$account_id' LIMIT 1";
$gotResults = mysqli_query($conn, $sql);
if ($gotResults) {
if (mysqli_num_rows($gotResults) > 0) {
while ($row = mysqli_fetch_array($gotResults)) {
// print_r($row['first_name']);

$update = isset($_GET['update']) && $_GET['update'] === 'true';

if (!isset($_SESSION['account_id'])) {
// Redirect to the login page if the user is not logged in
echo '<script>alert("User is not logged in, directing to login page.")</script>';
echo "<script> window.location.assign('login.php'); </script>";
exit();
}

// Display the user-specific information

$result = mysqli_query($conn, $sql); // Replace with data from the database
if ($result) {
$row = mysqli_fetch_array($result);
$user_email = $row['user_email'];
$pwd = $row['pwd'];
$first_name = $row['first_name'];
$last_name = $row['last_name'];
$role = $row['role'];
}
// Assuming $course_code is the course folder name
if (isset($course_code)) {
$_SESSION['course_folder_name'] = $course_code;
}
require('coursefolder.php');

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
?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta name="author" content="APC AcademX">
                <title>APC AcademX | Exam Maker</title>
                <link rel="stylesheet" href="./css/style.css">
                <link rel="stylesheet" href="./css/adminstyle.css">
                <link rel="stylesheet" href="./css/emstyle.css">
                <link rel="stylesheet" href="./css/myexamstyle.css">
                <link rel="stylesheet" href="./css/sidebar.css">
                <link rel="stylesheet" href="./css/header.css">
                <link rel="stylesheet" href="./css/homepage.css">
                <link rel="stylesheet" href="./css/helpbutton.css">
                <script src="https://kit.fontawesome.com/e85940e9f2.js" crossorigin="anonymous"></script>
            </head>

            <body>
                <!--OTHER CODE -->
                <navigation class="navbar">

                    <ul class="right-header">
        <li class="logo">
            <a href="<?php echo $redirect_url; ?>"><img id="logo" src="img/APC AcademX Logo.png"></a>
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
                            <a href="em.php">
                                <img src="img/back.png" style="padding-left: 5px">
                            </a>
                        </div>
                        <div class="help_buttonme">
                            <img src="img/help.png" alt="Help Icon">
                        </div>
                    </div>
                </navigation>


                <div class="column">

                    <!--header-->
                    <div class="emright">
                        <div class="contentem">

                            <div class="righthead">

                                <div class="adminmehead" style="margin-left: 50px ;display: flex">
                                    <p> My Exams </p>
                                    <?php if (!$update) : ?>
                                    <button class="addbutt" onclick="showPopup()" style="margin-left: 30px">
                                        <i class="fa-solid fa-circle-plus"></i>
                                    </button>
                                <?php endif; ?>
                                <script src="./myexams.js"></script>
                                
                                <div class="searchicon" style="display: flex; align-items: center; margin-left: auto" >
                                    <input type="text" class="searchbar" id="live_search" placeholder="Search a Course Folder...">
                                </div>
                                </div>

                                


                            </div>

                            <div class="system-list">

                            </div>
                            <!-- HIDDEN IS TO NOT DISPLAY IMMEDIATELY THE PLUS SIGN -->
                            <div class="popup-hidden">
                                <!-- POPUP-BG - this is for the black background behind the pop-up-hidden -->
                                <div class="popup_bg">
                                    <div class="Add_popup" >
                                    <form action="coursefolder.php" method="post">
                                        <!-- Add a hidden input field to indicate action -->
                                        <input type="hidden" name="action" id="action" value="add">

                                        <input type="hidden" name="update" value="<?php echo isset($_GET['update']) ? $_GET['update'] : 'false'; ?>">

                                        <?php if ($update == true) : ?>
                                            <p class="heading">Update Course Folder Information<img src="img/folder.png"></p>
                                        <?php else : ?>
                                            <div style="display: flex; align-items: center">
                                            <img src="img/folder.png">
                                            <p class="heading">Create a Course Folder</p>
                                            </div>
                                        <?php endif; ?>

                                        <input type="hidden" name="course_subject_id" value="<?php echo $course_subject_id ?>" readonly><br />

                                        <input type="hidden" name="account_id" value="<?php echo $account_id ?>" readonly><br />

                                        <div class="inputcolumn">
                                            <div>
                                            <label class="labelName" for="program_name">Program Name</label><br />
                                            <select class="input" name="program_name" required>
                                                <option value="" disabled selected>None Selected</option>
                                                <?php
                                                $sql = "SELECT program_name FROM program_name";
                                                $result = $mysqli->query($sql);

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $program = $row['program_name'];
                                                        $selected = ($program === $program_name) ? 'selected' : '';
                                                        echo "<option value=\"$program\" $selected>$program</option>";
                                                    }
                                                } else {
                                                    echo "<option value=\"\">No programs found</option>";
                                                }
                                                ?>
                                            </select>
                                            </div>
                                            <br/>
                                        </div>

                                        <div class="inputcolumn">

                                            <label class="labelName" for="course_code">Course Code</label>
                                            <select class="input" name="course_code" required>
                                                <option value="" disabled selected>None Selected</option>
                                                <?php
                                                $sql = "SELECT course_syllabus_id, course_code FROM course_syllabus";
                                                $result = $mysqli->query($sql);

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $course = $row['course_code'];
                                                        $selected = ($course === $course_code) ? 'selected' : '';
                                                        echo "<option value=\"$course\" $selected>$course</option>";
                                                    }
                                                } else {
                                                    echo "<option value=\"\">No courses found</option>";
                                                }
                                                ?>
                                            </select>
                                        
                                        </div>

                                        <input type="hidden" name="course_syllabus_id" value="<?php echo $course_subject_id ?>" readonly><br />
                                        <input type="hidden" name="course_topic_id" value="<?php echo $course_subject_id ?>" readonly><br />

                                        <?php if ($update == true) : ?>
                                            <button class="update" type="submit" name="update">Update</button>
                                        <?php else : ?>
                                            <div class="actionbuttons">
                                                <button class="cancel" onclick="window.location.href='myexams.php'" name = "cancel">Cancel</button>
                                                <span class="button-gap"></span> <!-- Add a span for the gap -->
                                                <button class="save" type="submit" name="save">Create</button>
                                                <?php endif; ?>      
                                            </div>
                                        <!-- <div class="cancelbutton">
                                            <a class="cancel" href="myexams.php">Cancel</a>
                                        </div> -->
                                    </form>
                                </div>
                                </div>
                            </div>
                            <!--line-->
                            <div class="adminemline">
                            </div>


                            <!--boxes-->

                            <?php
                            $result = $mysqli->query("SELECT * from prof_course_subject WHERE account_id = $account_id") or die(mysqli_error($mysqli));
                            if ($result->num_rows === 0) { ?>
                                <p class="header" style="margin-left: 50px;">You have no course folders.</p>
                            <?php } else { ?>
                                <div style="flex-wrap: wrap; margin-left: 30px;">
                                    <?php while ($row = $result->fetch_assoc()) :

                                        // **Fetch course_subject_id here:**
                                        $course_subject_id = $row['course_subject_id'];
                                        $courseCode = $row['course_code']; // Get the course code for the link
                                    ?>
                                        <section id="container2">
                                            <div class="emservices">
                                                <div class="mebox">
                                                    <div class="boxme">
                                                        <select onchange="handleAction(this)">
                                                            <option value="">Select Action</option>
                                                            <option value="myexams.php?edit=<?php echo $row['course_subject_id']; ?>&update=true">Edit</option>
                                                            <option value="coursefolder.php?delete=<?php echo $row['course_subject_id']; ?>">Delete</option>
                                                        </select>
                                                        <a href="topic.php?course_subject_id=<?php echo $course_subject_id; ?>&course_code=<?php echo urlencode($courseCode); ?>" class="fill-div">
                                                            <p class="malakingbox">
                                                                <?php echo $courseCode; ?>
                                                            </p>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                <?php endwhile;
                                } ?>
                                </div>
                        </div>
                    </div>
                </div>
            </body>
            <script>
                var update = false;

                function showPopup() {
                    // Select the popup element
                    const popup = document.querySelector(".popup-hidden");
                    // Remove the "hidden" class to display the popup
                    popup.classList.remove("popup-hidden");
                    // Prevent default link behavior
                    return false;
                }

                function showEditPopup(course_code, updateStatus) {
                    const popup = document.querySelector(".popup-hidden");
                    popup.classList.remove("popup-hidden");
                    document.getElementById("action").value = "edit";
                    document.querySelector("form").action = `coursefolder.php?edit=${course_code}`;
                    // Set the update variable to the provided status
                    update = updateStatus;
                }

                function handleAction(select) {
                    if (select.value.startsWith('coursefolder.php?delete=')) {
                        if (confirm('Are you sure you want to delete this course folder?')) {
                            window.location = select.value;
                        }
                    } else {
                        const editUrl = select.value;
                        const urlParams = new URLSearchParams(editUrl);
                        const updateParam = urlParams.get('update');
                        // Set update variable based on the updateParam
                        const updateStatus = updateParam === 'true';
                        showEditPopup(urlParams.get('edit'), updateStatus);
                    }
                }

                function handleSearchInput() {
        // Get the value of the search input
        const searchQuery = document.getElementById("live_search").value.trim().toLowerCase();
        // Get all course folder elements
        const courseFolders = document.querySelectorAll(".mebox");

        // Loop through each course folder
        courseFolders.forEach(folder => {
            // Get the text content of the course folder
            const folderText = folder.textContent.toLowerCase();
            // Check if the folder text contains the search query
            if (folderText.includes(searchQuery)) {
                // Show the folder if it matches the search query
                folder.style.display = "block";
            } else {
                // Hide the folder if it does not match the search query
                folder.style.display = "none";
            }
        });
    }

    // Attach an event listener to the search input
    document.getElementById("live_search").addEventListener("input", handleSearchInput);
            </script>
<?php
        }
    }
}

?>

            </html>