<link rel="stylesheet" href="css/buttons.css">
<link rel="stylesheet" href="css/tabledesign.css">
<?php
include("config/db.php");

// Function to check the count of Executive Directors
function countExecutiveDirectors($conn) {
    $execQuery = "SELECT COUNT(*) AS exec_count FROM account WHERE role = 'Executive Director'";
    $execResult = mysqli_query($conn, $execQuery);
    if ($execResult) {
        $execData = mysqli_fetch_assoc($execResult);
        return (int) $execData['exec_count'];
    }
    return 0; // In case of query failure, assume no Executive Director which should be handled as error
}

if (isset($_POST['input'])) {
    $input = $_POST['input'];
    $query = "SELECT * FROM account WHERE user_email LIKE '{$input}%' OR first_name LIKE '{$input}%' OR last_name LIKE '{$input}%' OR role LIKE '{$input}%' OR program_name LIKE '{$input}%'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Get the count of Executive Directors only once
        $execDirectorCount = countExecutiveDirectors($conn);
        ?>
        <div class="table" style="overflow: auto;">
            <table class="center">
                <thead>
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>User Role</th>
                        <th>User Program</th>
                        <th>User Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($row = mysqli_fetch_assoc($result)) {
                        $lname = $row['last_name'];
                        $fname = $row['first_name'];
                        $role = $row['role'];
                        $email = $row['user_email'];
                        $id = $row['account_id'];
                        $program= $row['program_name'];
                        ?>
                        <tr>
                            <td><?php echo $lname; ?></td>
                            <td><?php echo $fname; ?></td>
                            <td style="text-align: center">
                                <form action="adminsetcode.php" method="post">
                                    <input type="hidden" name="user_id" value="<?= $id; ?>">
                                    <select name="user_role" style = "font-size: 10px;"class="rolebutton" <?= ($execDirectorCount <= 1 && $role === 'Executive Director') ? 'disabled' : '' ?>>
                                        <?php
                                        $sql = "SELECT * FROM role";
                                        $role_data = mysqli_query($conn, $sql);
                                        
                                        while ($role_row = mysqli_fetch_assoc($role_data)) {
                                            $selected = ($role == $role_row["role"]) ? "selected" : "";
                                            echo "<option value='" . $role_row["role"] . "' $selected>" . $role_row["role"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <?php if ($execDirectorCount > 1 || $role !== 'Executive Director'): ?>
                                        <button type="submit" class="btn btn-primary" name="update_admin_data" onclick="return confirm('Are you sure you want to update <?= $fname . ' ' . $lname; ?> to a new role?')" style = "font-size: 10px;">Update</button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-primary" disabled>Update Denied</button>
                                    <?php endif; ?>
                                </form>

                                <td style="text-align: center">
                                <form action="adminsetcode.php" method="post">
                                    <input type="hidden" name="user_id" value="<?= $id; ?>">
                                    <select name="user_program" class="rolebutton" style = "font-size: 10px;">
                                        <?php
                                        $sql = "SELECT * FROM program_name";
                                        $program_data = mysqli_query($conn, $sql);
                                        
                                        while ($program_row = mysqli_fetch_assoc($program_data)) {
                                            $selected = ($program == $program_row["program_name"]) ? "selected" : "";
                                            echo "<option value='" . $program_row["program_name"] . "' $selected>" . $program_row["program_name"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                        <button type="submit" class="btn btn-primary" name="update_program_data" onclick="return confirm('Are you sure you want to update <?= $fname . ' ' . $lname; ?> to a new program?')" style = "font-size: 10px;">Update</button>
                                </form>
                                    </td>
                                    </td>
                            <td><?php echo $email; ?></td>
                            <td style="text-align: center">
                                <form action="adminsetcode.php" method="post">
                                    <input type="hidden" name="user_delete" value="<?= $id; ?>">
                                    <?php if ($execDirectorCount > 1 || $role !== 'Executive Director'): ?>
                                        <button type="submit" class="btn btn-danger" style = "font-size: 10px;" name="delete_admin_data" onclick="return confirm('Are you sure you want to delete <?= $fname . ' ' . $lname; ?>?')">Delete</button>
                                    <?php else: ?>  
                                        <button type="button" class="btn btn-danger" disabled>Deletion Denied</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo "No results found";
    }
}
?>
