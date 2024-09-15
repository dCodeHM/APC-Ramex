<!-- AcademicYearExam.php -->
<?php
session_start();

include("config/RAMeXSO.php");
include("config/functions.php");

$user_data = check_login($conn_soe);

$account_id = $_SESSION['account_id'];

$sql = "SELECT * FROM account WHERE account_id = '$account_id' LIMIT 1";
$gotResults = mysqli_query($conn_soe, $sql);
if ($gotResults) {
if (mysqli_num_rows($gotResults) > 0) {
while ($row = mysqli_fetch_array($gotResults)) {
// print_r($row['first_name']);

// $update = isset($_GET['update']) && $_GET['update'] === 'true';

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
// if (isset($course_code)) {
// $_SESSION['course_folder_name'] = $course_code;
// }
// require('AYpageDB.php');

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

function getLatestCourseInfo($conn_soe) {
    $query = "SELECT acy_id, term, submitted 
              FROM course 
              WHERE submitted = 0 
              ORDER BY course_id DESC, acy_id DESC, term DESC 
              LIMIT 1";
    $result = mysqli_query($conn_soe, $query);
    return mysqli_fetch_assoc($result);
}

$latestCourseInfo = getLatestCourseInfo($conn_soe);
$latest_acy_id = $latestCourseInfo['acy_id'];
$latest_term = $latestCourseInfo['term'];

// Define the updateProfCourseSubject function
function updateProfCourseSubject($conn_ramex, $conn_soe, $account_id) {
    // Get the latest course information
    $latest_course_query = "SELECT acy_id, term FROM course WHERE submitted = 0 ORDER BY course_id DESC LIMIT 1";
    $latest_course_result = mysqli_query($conn_soe, $latest_course_query);
    $latest_course = mysqli_fetch_assoc($latest_course_result);

    if ($latest_course) {
        $latest_acy_id = $latest_course['acy_id'];
        $latest_term = $latest_course['term'];

        // Check if this entry already exists in prof_course_subject
        $check_query = "SELECT * FROM prof_course_subject WHERE account_id = ? AND acy_id = ? AND term = ?";
        $check_stmt = mysqli_prepare($conn_ramex, $check_query);
        mysqli_stmt_bind_param($check_stmt, "iii", $account_id, $latest_acy_id, $latest_term);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) == 0) {
            // If it doesn't exist, insert a new entry
            $insert_query = "INSERT INTO prof_course_subject (account_id, acy_id, term) VALUES (?, ?, ?)";
            $insert_stmt = mysqli_prepare($conn_ramex, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "iii", $account_id, $latest_acy_id, $latest_term);
            mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);
        }

        mysqli_stmt_close($check_stmt);
    }
}

updateProfCourseSubject($conn_ramex, $conn_soe, $account_id);
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
                <link rel="stylesheet" href="./css/AcademicYearExam.css">
                <link rel="stylesheet" href="./css/dots.css?v=<?php echo time(); ?>">
                <script src="https://kit.fontawesome.com/e85940e9f2.js" crsossorigin="anonymous"></script>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        <div class="help_buttonAY">
                            <img src="img/help.png" alt="Help Icon">
                        </div>
                    </div>
                </navigation>


                <div class="column">

                    <!--header-->
                    <div class="emright">
                        <div class="contentem">

                            <div class="righthead">

                            <div class="adminmehead" style="margin-left: 50px; display: flex">
                        <p>Academic Year</p>
                        <div class="searchicon" style="display: flex; align-items: center; margin-left: auto">
                        <input type="text" class="searchbar" id="live_search" placeholder="Search an Academic Year...">
                        </div>
                    </div>
                            </div>

                            <!--line-->
                            <div class="adminemline">
                            </div>

<!--boxes-->
<section id="container2" style="cursor:pointer">
    <div class="emservices">
        <?php
// Get the program_id for the current user
$query_program_id = "SELECT pl.program_id 
FROM account a
JOIN program_name pl ON a.program_name = pl.program_name
WHERE a.account_id = ?";
$stmt_program_id = mysqli_prepare($conn_soe, $query_program_id);
mysqli_stmt_bind_param($stmt_program_id, "i", $account_id);
mysqli_stmt_execute($stmt_program_id);
$result_program_id = mysqli_stmt_get_result($stmt_program_id);
$row_program_id = mysqli_fetch_assoc($result_program_id);
$user_program_id = $row_program_id['program_id'];
mysqli_stmt_close($stmt_program_id);

// Query to fetch all academic years for the user's program
$query_academic_years = "SELECT acy_id, academic_year
                         FROM academic_year
                         WHERE program_id = ?
                         ORDER BY academic_year DESC";
$stmt_academic_years = mysqli_prepare($conn_soe, $query_academic_years);
mysqli_stmt_bind_param($stmt_academic_years, "i", $user_program_id);
mysqli_stmt_execute($stmt_academic_years);
$result_academic_years = mysqli_stmt_get_result($stmt_academic_years);

if ($result_academic_years) {
    while ($row_acy = mysqli_fetch_assoc($result_academic_years)) {
        $acy_id = $row_acy['acy_id'];
        $academic_year = $row_acy['academic_year'];
        ?>
<div class="mebox">
            <div class="AYboxme">
                <div class="AYheader">
                    <div class="AYtitle-container">
                        <svg class="AYtoggle-arrow" viewBox="0 0 24 24" onclick="toggleTerms(this)">
                            <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="AYacademic-year">Academic Year: <?php echo $academic_year; ?></p>
                    </div>
                </div>
                <div class="AYfill-div">
                    <div class="AYrow-container" style="display: none;">
                        <div class="terms-row">
                        <?php
                        for ($term = 1; $term <= 3; $term++) {
                            // Query to check if there's a course for this academic year and term
                            $query_course = "SELECT submitted FROM course 
                                             WHERE acy_id = ? AND term = ? AND user_id = ? 
                                             LIMIT 1";
                            $stmt_course = mysqli_prepare($conn_soe, $query_course);
                            mysqli_stmt_bind_param($stmt_course, "isi", $acy_id, $term, $account_id);
                            mysqli_stmt_execute($stmt_course);
                            $result_course = mysqli_stmt_get_result($stmt_course);
                            
                            if (mysqli_num_rows($result_course) > 0) {
                                $course = mysqli_fetch_assoc($result_course);
                                $submitted = $course['submitted'];
                                if ($submitted == 1) {
                                    $term_class = 'complete-term';
                                    $term_status = 'Complete';
                                } else {
                                    $term_class = 'ongoing-term';
                                    $term_status = 'Ongoing';
                                }
                            } else {
                                $term_class = 'not-available';
                                $term_status = 'Not Available';
                                $submitted = 0;
                            }
                            
                            mysqli_stmt_close($stmt_course);

                            $term_text = "Term $term";

                            echo "<div class='term-container'>";
                            echo "<div class='AYmalakingbox {$term_class}' onclick='handleTermClick({$acy_id}, {$term}, {$submitted}, \"{$term_class}\")' data-status='{$term_status}'>";
                            echo "<span class='term-text'>{$term_text}</span>";
                            echo "<span class='term-status'>{$term_status}</span>";
                            echo "</div>";
                            echo "</div>";
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "Error fetching academic years: " . mysqli_error($conn_soe);
}
mysqli_stmt_close($stmt_academic_years);
?>
</div>
</section>

<script>
function toggleTerms(arrow) {
        const boxme = arrow.closest('.AYboxme');
        const rowContainer = boxme.querySelector('.AYrow-container');
        
        arrow.classList.toggle('open');
        
        if (rowContainer.style.display === 'none' || rowContainer.style.display === '') {
            rowContainer.style.display = 'block';
            rowContainer.style.maxHeight = rowContainer.scrollHeight + "px";
            rowContainer.style.opacity = 1;
        } else {
            rowContainer.style.maxHeight = null;
            rowContainer.style.opacity = 0;
            setTimeout(() => { rowContainer.style.display = 'none'; }, 300);
        }
    }

    function toggleCourses(termElement) {
    const coursesContainer = termElement.nextElementSibling;
    
    if (coursesContainer.style.display === 'none' || coursesContainer.style.display === '') {
        coursesContainer.style.display = 'block';
    } else {
        coursesContainer.style.display = 'none';
    }
}

function handleSearchInput() {
    const searchQuery = document.getElementById("live_search").value.trim().toLowerCase();
    const academicYears = document.querySelectorAll(".AYboxme");

    academicYears.forEach(ay => {
        const ayText = ay.textContent.toLowerCase();
        if (ayText.includes(searchQuery)) {
            ay.closest('.mebox').style.display = "block";
        } else {
            ay.closest('.mebox').style.display = "none";
        }
    });
}

document.getElementById("live_search").addEventListener("input", handleSearchInput);

</script>

<?php
mysqli_close($conn_ramex);
mysqli_close($conn_soe);
?>
    </div>
<?php } ?>
</div>
</div>
</div>
</div>
                        <!-- Add this HTML for the popup at the end of the body tag -->
                        <div id="termPopup" class="term-popup">
    <div class="term-popup-content">
        <span class="close-popup">&times;</span>
        <p id="popupMessage"></p>
    </div>
</div>
</body>


<script>
function redirectToMyExams(acyId, term, submitted) {
    window.location.href = `myexams.php?acy_id=${acyId}&term=${term}&submitted=${submitted}`;
}

function handleSearchInput() {
    const searchQuery = document.getElementById("live_search").value.trim().toLowerCase();
    const academicYearBoxes = document.querySelectorAll(".mebox");

    academicYearBoxes.forEach(box => {
        const academicYearText = box.querySelector(".AYacademic-year").textContent.toLowerCase();
        if (academicYearText.includes(searchQuery)) {
            box.style.display = "block";
        } else {
            box.style.display = "none";
        }
    });
}

// Attach an event listener to the search input
document.getElementById("live_search").addEventListener("input", handleSearchInput);

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

                function handleSearchInput() {
        const searchQuery = document.getElementById("live_search").value.trim().toLowerCase();
        const academicYears = document.querySelectorAll(".AYboxme");

        academicYears.forEach(ay => {
            const ayText = ay.textContent.toLowerCase();
            if (ayText.includes(searchQuery)) {
                ay.closest('.mebox').style.display = "block";
            } else {
                ay.closest('.mebox').style.display = "none";
            }
        });
    }

    // Attach an event listener to the search input
    document.getElementById("live_search").addEventListener("input", handleSearchInput);

    function handleTermClick(acyId, term, submitted, termClass) {
    if (termClass === 'not-available') {
        showPopup("This term is not available.");
    } else {
        redirectToMyExams(acyId, term, submitted);
    }
}

function showPopup(message) {
    const popup = document.getElementById("termPopup");
    const popupMessage = document.getElementById("popupMessage");
    popupMessage.textContent = message;
    popup.style.display = "block";
}

// Close the popup when clicking on the close button or outside the popup
document.querySelector(".close-popup").onclick = function() {
    document.getElementById("termPopup").style.display = "none";
}

window.onclick = function(event) {
    const popup = document.getElementById("termPopup");
    if (event.target == popup) {
        popup.style.display = "none";
    }
}
            </script>
<?php
        }
    }

?>

            </html>