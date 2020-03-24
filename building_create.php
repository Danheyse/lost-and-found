<?php
     
    require '../database/database.php';
    session_start();
    if(!isset($_SESSION["person_id"])){ // if "user" not set,
    	session_destroy();
    	header('Location: login.php');     // go to login page
    	exit;
    }
    if ( !empty($_POST)) {
        // keep track validation errors
        $buildingError = null;
         
        // keep track post values
        $building = $_POST['building'];
        

        // validate input
        $valid = true;

        if (empty($building)) {
            $descriptionError = 'Please enter building';
            $valid = false;
        }
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO buildings (Name) values(?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($building));
            Database::disconnect();
            header("Location: create.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
     
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Add new Location</h3>
                    </div>
             
                    <form class="form-horizontal" action="building_create.php" method="post">
                      <div class="control-group <?php echo !empty($itemNameError)?'error':'';?>">
                        <label class="control-label">New Area Name</label>
                        <div class="controls">
                            <input name="building" type="text"  placeholder="Building Name" value="<?php echo !empty($building)?$building:'';?>">
                            <?php if (!empty($buildingError)): ?>
                                <span class="help-inline"><?php echo $buildingError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
>


                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Create</button>
                          <a class="btn" href="create.php">Back</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>