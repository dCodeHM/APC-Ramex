<?php 
  include("config/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funda of Web IT</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <?php 
                    if(isset($_SESSION['status']))
                    {
                        ?>
                        
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php
                        unset($_SESSION['status']);
                    }
                ?>

                <div class="card mt-5">
                    <div class="card-header">
                        <h4>How to Update Data by ID into Database in PHP MySQL</h4>
                    </div>
                    <div class="card-body">

                        <form action="code.php" method="POST">
                            <?php
                                $sql = "SELECT * FROM  student WHERE id = 1";
                                $gotResults = mysqli_query($connection, $sql);
                                if ($gotResults){
                                if(mysqli_num_rows($gotResults)>0){
                                    while($row = mysqli_fetch_array($gotResults)){
                                    // print_r($row['first_name']);
                            ?>
                            <div class="form-group mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $row['stud_name']; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Class</label>
                                <input type="text" name="class" class="form-control" value="<?php echo $row['stud_class']; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Phone No.</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo $row['stud_phone']; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="update_stud_data" class="btn btn-primary">Update Data</button>
                            </div>
                            
                            <?php
                                }
                            }
                        }

?>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
</html>