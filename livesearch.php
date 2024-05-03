<?php
include("config/db.php");

if (isset($_POST['input'])) {
    $input = $_POST['input'];

    $query = "SELECT * FROM account WHERE user_email LIKE '{$input}%' OR first_name LIKE '{$input}%' OR last_name LIKE '{$input}%' OR role LIKE '{$input}%'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        ?>
        <div class="table" style="overflow: auto; padding-top: 200px">
            <div class="tablecontent">
                <table class="center">
                    <thead>  
                        <tr>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>User Role</th>
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
                            ?>
                            <tr>
                                <td><?php echo $lname; ?></td>
                                <td><?php echo $fname; ?></td>
                                <td>
                                    <form action="adminsetcode.php" method="post">
                                        <input type="hidden" name="user_id" value="<?= $id; ?>">
                                        <select name="user_role" class="form-control">
                                            <?php
                                            // Query to get current role of user
                                            $sql = "SELECT role FROM account WHERE account_id = '$id'";
                                            $role_result = mysqli_query($conn, $sql);
                                            
                                            if ($role_result && mysqli_num_rows($role_result) > 0) {
                                                $currentRole = mysqli_fetch_assoc($role_result)['role'];  // Get current role
                                                
                                                // Query to fetch all roles from database
                                                $sql = "SELECT * FROM role";
                                                $role_data = mysqli_query($conn, $sql);
                                                
                                                while ($row = mysqli_fetch_assoc($role_data)) {
                                                    $selected = $currentRole == $row["role"] ? "selected" : ""; // Set selected attribute dynamically
                                                    echo "<option value='" . $row["role"] . "' $selected>" . $row["role"] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>No Role Found</option>";  // Handle no role case
                                            }
                                            ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary" name = "update_admin_data">Update</button>
                                    </form>
                                </td>
                                <td><?php echo $email; ?></td>
                                <td>
                                    <form action="adminsetcode.php" method="post">
                                        <input type="hidden" name="user_delete" value="<?= $id; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    } else {
        echo "No result found";
    }
}

