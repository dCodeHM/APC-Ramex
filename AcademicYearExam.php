<!-- AcademicYearExam.php -->
<?php
session_start();

include("config/RAMeXSO.php");
include("config/functions.php");

$user_data = check_login($conn_ramex);

$account_id = $_SESSION['account_id'];
$sql = "SELECT * FROM  account WHERE account_id = '$account_id' LIMIT 1";
$gotResults = mysqli_query($conn_ramex, $sql);
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

$result = mysqli_query($conn_ramex, $sql); // Replace with data from the database
if ($result) {
$row = mysqli_fetch_array($result);
$user_email = $row['user_email'];
$pwd = $row['pwd'];
$first_name = $row['first_name'];
$last_name = $row['last_name'];
$role = $row['role'];
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

                            <div class="adminmehead" style="margin-left: 50px; display: flex">
                        <p>Academic Year</p>
                        <div class="searchicon" style="display: flex; align-items: center; margin-left: auto">
                            <input type="text" class="searchbar" id="live_search" placeholder="Search a Course Folder...">
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
        // Query to fetch all distinct academic years, including future ones
        $query_academic_years = "SELECT DISTINCT pcs.acy_id, ay.academic_year 
                                 FROM prof_course_subject pcs
                                 JOIN soe_assessment_db.academic_year ay ON pcs.acy_id = ay.acy_id
                                 ORDER BY ay.academic_year DESC";
        $result_academic_years = mysqli_query($conn_ramex, $query_academic_years);

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
                                    // Query to fetch courses for this academic year and term
                                    $query_courses = "SELECT * FROM prof_course_subject WHERE acy_id = ? AND term = ? AND account_id = ?";
                                    $stmt_courses = mysqli_prepare($conn_ramex, $query_courses);
                                    mysqli_stmt_bind_param($stmt_courses, "isi", $acy_id, $term, $account_id);
                                    mysqli_stmt_execute($stmt_courses);
                                    $result_courses = mysqli_stmt_get_result($stmt_courses);

                                    $term_class = mysqli_num_rows($result_courses) > 0 ? 'incomplete-term' : 'not-available';
                                    $term_text = $term_class == 'not-available' ? "Term $term (Not Available)" : "Term $term";

                                    echo "<div class='term-container'>";
                                    echo "<div class='AYmalakingbox {$term_class}' onclick='redirectToMyExams($acy_id, $term)'>{$term_text}</div>";
                                    echo "<div class='courses-container' style='display: none;'>";

                                    while ($course = mysqli_fetch_assoc($result_courses)) {
                                        echo "<div class='course' data-course-code='{$course['course_code']}'>{$course['course_code']}</div>";
                                    }

                                    echo "</div>"; // Close courses-container
                                    echo "</div>"; // Close term-container

                                    mysqli_stmt_close($stmt_courses);
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
        echo "Error fetching academic years: " . mysqli_error($conn_ramex);
    }
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
        const termContainer = termElement.closest('.term-container');
        const termIndex = Array.from(termContainer.parentNode.children).indexOf(termContainer) + 1;
        const coursesContainer = termElement.closest('.AYrow-container').querySelector(`.courses.term-${termIndex}`);
        
        document.querySelectorAll('.courses').forEach(el => el.style.display = 'none');
        
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
</body>


<script>
    function redirectToMyExams(acyId, term, courseCode) {
        window.location.href = `myexams.php?acy_id=${acyId}&term=${term}&course_code=${encodeURIComponent(courseCode)}`;
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
            </script>
<?php
        }
    }

?>

            </html>