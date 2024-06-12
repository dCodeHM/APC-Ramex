<?php
$course_subject_id = 0;
$course_subject = '';
$course_code = '';
$course_syllabus_id = 0;
$course_topic_id = 0;
$update = false;

require('config/db.php');

// Retrieve all the created course_codes
$query = "SELECT course_code FROM prof_course_subject";
$result = $mysqli->query($query);

// Display the course_codes
if ($result->num_rows > 0) {
    echo "<h3>Created Course Codes:</h3>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['course_code'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "No course codes found.";
}