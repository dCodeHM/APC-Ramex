<?php
include("config/db.php");
if (isset($_POST['input'])){
  $input = $_POST['input'];

  $query = "SELECT * FROM account WHERE user_email LIKE '{$input}%' OR first_name LIKE '{$input}%' OR last_name LIKE '{$input}%' OR role LIKE '{$input}%'";

  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0){?>
  <div class="table" style="overflow: auto; padding-top: 200px">
  <div class="tablecontent">
    <table class = "center">
    <thead>  
      <tr>
        <th>User Email</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>User Role</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      while ($row = mysqli_fetch_assoc($result)){

        $email = $row['user_email'];
        $fname = $row['first_name'];
        $lname = $row['last_name'];
        $role = $row['role'];
        ?>

      <tr>
        <td><?php echo $email; ?></td>
        <td><?php echo $fname; ?></td>
        <td><?php echo$lname; ?></td>
        <td><?php echo $role; ?></td>
      </tr>

      <?php
      }
      ?>
    </tbody>
    </table>
                    </div>
  </div>
    <?php
  }
  else{
    echo "No result found";
  }
}


