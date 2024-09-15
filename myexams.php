<!-- STOP IF NAKITA MO TO PAGSABIHIN NATAPOS MO NA PAG STORE NG SYLLABUS_COURSE_ID *MISSING IS DAPAT YUNG COURSE_CODE UNG NAKA DISPLAY HINDI YUNG SYLLABUS_COURSE_ID -->

<!-- myexams.php -->
<?php
session_start();

include("config/RAMeXSO.php");
include("config/functions.php");
global $mysqli_soe, $mysqli_ramex, $conn_ramex;


$user_data = check_login($conn_soe);

$account_id = $_SESSION['account_id'];

$acy_id = isset($_GET['acy_id']) ? intval($_GET['acy_id']) : 0;
$term = isset($_GET['term']) ? intval($_GET['term']) : 0;
$submitted = isset($_GET['submitted']) ? intval($_GET['submitted']) : 0;

$sql = "SELECT * FROM soe_assessment_db.account WHERE account_id = '$account_id' LIMIT 1";
$gotResults = mysqli_query($conn_soe, $sql);
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

// Get academic year and term from URL parameters
$acy_id = isset($_GET['acy_id']) ? intval($_GET['acy_id']) : null;
$term = isset($_GET['term']) ? intval($_GET['term']) : null;

function getSyllabusCourses($connection) {
    $courses = array();
    $sql = "SELECT syllabus_course_id, course_code, program_id FROM syllabus_course ORDER BY course_code";
    $result = $connection->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }

    return $courses;
}

// $syllabusCourses = getSyllabusCourses($mysqli);
$syllabusCourses = getSyllabusCourses($mysqli_soe);


// New function to update the submitted value in prof_course_subject
function updateSubmittedValue($conn_ramex, $account_id, $acy_id, $term, $submitted) {
    $update_query = "UPDATE prof_course_subject 
                     SET submitted = ? 
                     WHERE account_id = ? AND acy_id = ? AND term = ?";
    $update_stmt = mysqli_prepare($conn_ramex, $update_query);
    mysqli_stmt_bind_param($update_stmt, "iiii", $submitted, $account_id, $acy_id, $term);
    $result = mysqli_stmt_execute($update_stmt);
    mysqli_stmt_close($update_stmt);

    if ($result) {
        // echo "<script>console.log('Submitted value updated successfully');</script>";
    } else {
        // echo "<script>console.log('Error updating submitted value: " . mysqli_error($conn_ramex) . "');</script>";
    }
}

// Call the function to update the submitted value
if ($acy_id && $term && isset($_GET['submitted'])) {
    updateSubmittedValue($conn_ramex, $account_id, $acy_id, $term, $submitted);
}

?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta name="author" content="APC AcademX">
                <title>APC AcademX | Exam Maker</title>
                <link rel="system icon" type="x-icon" href="./img/icon.png">
                <link rel="stylesheet" href="./css/style.css">
                <link rel="stylesheet" href="./css/adminstyle.css">
                <link rel="stylesheet" href="./css/emstyle.css">
                <link rel="stylesheet" href="./css/myexamstyle.css?v=<?php echo time(); ?>">
                <link rel="stylesheet" href="./css/sidebar.css">
                <link rel="stylesheet" href="./css/header.css">
                <link rel="stylesheet" href="./css/homepage.css">
                <link rel="stylesheet" href="./css/helpbutton.css">
                <link rel="stylesheet" href="./css/dots.css?v=<?php echo time(); ?>">
                <script src="https://kit.fontawesome.com/e85940e9f2.js" crsossorigin="anonymous"></script>
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
                            <a href="AcademicYearExam.php">
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
    <input type="hidden" name="acy_id" value="<?php echo $acy_id; ?>">
    <input type="hidden" name="term" value="<?php echo $term; ?>">
    <input type="hidden" name="submitted" value="<?php echo $submitted; ?>">
    <input type="hidden" name="update" value="<?php echo isset($_GET['update']) ? $_GET['update'] : 'false'; ?>">
    <input type="hidden" name="account_id" value="<?php echo $account_id; ?>">

                                        <?php if ($update == true) : ?>
                                            <p class="heading">Update Course Folder Information<img src="img/folder.png"></p>
                                        <?php else : ?>
                                            <div style="display: flex; align-items: center">
                                            <img src="img/folder.png">
                                            <p class="heading">Create a Course Folder</p>
                                            </div>
                                        <?php endif; ?>
                                        

                                        <input type="hidden" name="course_subject_id" value="<?php echo $course_subject_id ?>" readonly><br/>

                                        <input type="hidden" name="account_id" value="<?php echo $account_id ?>" readonly><br/>


                                        <div class="inputcolumn">
        <label class="labelName" for="course_code">Course Code</label>
        <select class="input" name="course_code" id="course_code_select" required>
            <option value="" disabled selected>None Selected</option>
            <?php
// Assuming $program_name is available from the user's session or profile
$program_name = $user_data['program_name']; // Make sure this is correctly set

$sql = "SELECT sc.syllabus_course_id, sc.course_code 
        FROM soe_assessment_db.syllabus_course sc
        JOIN soe_assessment_db.program_name pl ON sc.program_id = pl.program_id
        LEFT JOIN ramexdb.prof_course_subject pcs 
            ON sc.syllabus_course_id = pcs.syllabus_course_id 
            AND pcs.account_id = ? 
            AND pcs.acy_id = ? 
            AND pcs.term = ?
        WHERE pcs.syllabus_course_id IS NULL
        AND pl.program_name = ?  -- Filter by program_name
        ORDER BY sc.course_code";

$stmt = $conn_soe->prepare($sql);
$stmt->bind_param("iiis", $account_id, $acy_id, $term, $program_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $syllabus_course_id = $row['syllabus_course_id'];
        $course_code = $row['course_code'];
        echo "<option value=\"$syllabus_course_id|$course_code\">$course_code</option>";
    }
} else {
    echo "<option value=\"\">No courses available for your program</option>";
}
$stmt->close();
?>
        </select>
    </div>

    <input type="hidden" name="syllabus_course_id" id="syllabus_course_id" value="">
    <input type="hidden" name="selected_course_code" id="selected_course_code" value="">

<?php if ($update == true) : ?>
    <button class="update" type="submit" name="update">Update</button>
<?php else : ?>
    <div class="actionbuttons">
        <button class="cancel" type="button" onclick="cancelForm(<?php echo $acy_id; ?>, <?php echo $term; ?>, <?php echo $submitted; ?>)">Cancel</button>
        <span class="button-gap"></span>
        <button class="save" type="submit" name="save" onclick="return confirmCreate()">Create</button>
    </div>
    <script>
function cancelForm(acyId, term, submitted) {
    if (confirm("Are you sure you want to cancel? Any selected program name and course code will not be saved.")) {
        reloadPage(acyId, term, submitted);
    }
}

function confirmCreate() {
    if (confirm("Are you sure you want to create the course folder?")) {
        // The form will be submitted, and the page will be reloaded by coursefolder.php
        return true;
    }
    return false;
}

function reloadPage(acyId, term, submitted) {
    window.location.href = `myexams.php?acy_id=${acyId}&term=${term}&submitted=${submitted}`;
}
</script>
<?php endif; ?>  
                                        
                                    </form>
                                </div>
                                </div>
                            </div>
                            <!--line-->
                            <div class="adminemline">
                            </div>

<!--boxes-->
<?php
                // Modify the query to include academic year and term filtering
                $query = "SELECT cs.*, COUNT(ct.course_topic_id) as topic_count, 
                sc.course_code, sc.syllabus_course_id
         FROM prof_course_subject cs
         LEFT JOIN prof_course_topic ct ON cs.course_subject_id = ct.course_subject_id
         LEFT JOIN soe_assessment_db.syllabus_course sc ON cs.syllabus_course_id = sc.syllabus_course_id
         WHERE cs.account_id = ?";
      
      $params = array($account_id);
      $types = "i";
      
      if ($acy_id !== null && $term !== null) {
          $query .= " AND cs.acy_id = ? AND cs.term = ?";
          $params[] = $acy_id;
          $params[] = $term;
          $types .= "ii";
      }
      
      $query .= " GROUP BY cs.course_subject_id";
      
      $stmt = $mysqli_ramex->prepare($query);
      $stmt->bind_param($types, ...$params);
      $stmt->execute();
      $result = $stmt->get_result();
      
      if ($result->num_rows === 0) { ?>
          <p class="header" style="margin-left: 50px;">No course folders found for the selected criteria.</p>
<?php } else { ?>
    <div style="flex-wrap: wrap; margin-left: 30px;">
        <?php while ($row = $result->fetch_assoc()) :
            $course_subject_id = $row['course_subject_id'];
            $courseCode = $row['course_code'];
            $syllabus_course_id = $row['syllabus_course_id']; // Fetch the syllabus_course_id
            $hasTopics = $row['topic_count'] > 0;
        ?>
        <section id="container2" style="cursor:pointer">
            <div class="emservices">
                <div class="mebox">
                    <div class="boxme">
                    <?php 
$courseCode = trim($courseCode); // Trim the course code to remove any unwanted characters
?>
                        <div class="fill-div" onclick="handleClick(event, '<?php echo $course_subject_id; ?>', '<?php echo urlencode($courseCode); ?>', '<?php echo $syllabus_course_id; ?>', '<?php echo $account_id; ?>', '<?php echo $acy_id; ?>', '<?php echo $term; ?>')">
                        <div class="options">
    <img src="./img/x.png" alt="Delete" onclick="confirmDelete(event, '<?php echo $row['course_subject_id']; ?>', <?php echo $acy_id; ?>, <?php echo $term; ?>, <?php echo $submitted; ?>)" style="display: <?php echo $hasTopics ? 'none' : 'block'; ?>">
</div>
                            <p class="malakingbox">
                                <?php echo $courseCode; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endwhile; ?>
    </div>
<?php } $stmt->close(); ?>
</div>
</div>
</div>
</div>
</body>
<script>
function handleClick(event, courseSubjectId, courseCode, syllabusCourseId, accountId, acyId, term) {
    event.preventDefault(); // Prevent the default link behavior

    // Check if the delete button was clicked
    if (event.target.classList.contains('options')) {
        return; // Do nothing if the delete button was clicked
    }

    // Redirect to the topic.php page with all the parameters
    window.location.href = "topic.php?course_subject_id=" + courseSubjectId + 
                           "&course_code=" + encodeURIComponent(courseCode) + 
                           "&syllabus_course_id=" + syllabusCourseId + 
                           "&account_id=" + accountId + 
                           "&acy_id=" + acyId + 
                           "&term=" + term;
                           console.log("Redirecting with syllabus_course_id:", syllabusCourseId); // Add this line for debugging
}

function confirmDelete(event, courseSubjectId, acyId, term, submitted) {
    event.stopPropagation(); // Prevent the event from propagating to parent elements

    if (confirm("Are you sure you want to delete this course folder?")) {
        // User clicked Yes, proceed with deletion
        deleteCourseFolder(courseSubjectId, acyId, term, submitted);
    } else {
        // User clicked No, just reload the page
        reloadPage(acyId, term, submitted);
    }
}

function deleteCourseFolder(courseSubjectId, acyId, term, submitted) {
    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Set up the request
    xhr.open("GET", `coursefolder.php?delete=${courseSubjectId}&acy_id=${acyId}&term=${term}&submitted=${submitted}`, true);

    // Set up the callback function
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Deletion successful, reload the page
                reloadPage(acyId, term, submitted);
            } else {
                // Deletion failed, display an error message and reload the page
                alert("Failed to delete the course folder. Please try again.");
                reloadPage(acyId, term, submitted);
            }
        }
    };

    // Send the request
    xhr.send();
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

function handleAction(select) {
    var selectedValue = select.value;
    if (selectedValue !== "") {
        window.location.href = selectedValue;
    }
    select.value = "";
}


function handleAction(select) {
    var selectedValue = select.value;
    if (selectedValue !== "") {
        select.parentNode.style.display = "none";
        window.location.href = selectedValue;
    }
    select.value = "";
}

                var update = false;

                function showPopup() {
                    // Select the popup element
                    const popup = document.querySelector(".popup-hidden");
                    // Remove the "hidden" class to display the popup
                    popup.classList.remove("popup-hidden");
                    // Prevent default link behavior
                    return false;
                }

                function showEditPopup(course_subject_id, updateStatus) {
    const popup = document.querySelector(".popup-hidden");
    popup.classList.remove("popup-hidden");
    document.getElementById("action").value = "edit";
    document.querySelector("form").action = `coursefolder.php?edit=${course_subject_id}`;
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

    // Attach an event listener to the course code select element
    document.getElementById('course_code_select').addEventListener('change', function() {
    var selectedOption = this.value.split('|');
    document.getElementById('syllabus_course_id').value = selectedOption[0];
    document.getElementById('selected_course_code').value = selectedOption[1];
    console.log('Selected syllabus_course_id:', selectedOption[0]);
    console.log('Selected course_code:', selectedOption[1]);
});

            </script>
<?php
        }
    }
}

?>

            </html>