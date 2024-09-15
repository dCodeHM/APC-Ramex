<?php
// Establish database connection
include("config/RAMeXSO.php");

$activity_id = $_GET['activity_id'];

$query = "SELECT item_name, total_points, clo_id_range FROM numbered_activity WHERE activity_id = ?";
$stmt = $conn_soe->prepare($query);
$stmt->bind_param("i", $activity_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h3>Numbered Activity Details</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Item Name</th><th>Total Points</th><th>CLO ID Range</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        $item_names = explode('~', $row['item_name']);
        $total_points = explode('~', $row['total_points']);
        $clo_id_ranges = explode('~', $row['clo_id_range']);
        
        for ($i = 0; $i < count($item_names); $i++) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($item_names[$i]) . "</td>";
            echo "<td>" . htmlspecialchars($total_points[$i]) . "</td>";
            echo "<td>" . htmlspecialchars($clo_id_ranges[$i]) . "</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
} else {
    echo "No numbered activity details found for this activity.";
}

$stmt->close();
$conn_soe->close();
?>