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
        $itemNameError = null;
        $person_idError = null;
        $descriptionError = null;
        $buildingError = null;
        $pictureError = null;
         
        // keep track post values
        $itemName = $_POST['name'];
        $person_id = $_SESSION["person_id"];
        $description = $_POST['description'];
        $building = $_POST['building'];
        
         // initialize $_FILES variables
	    $tmpName  = $_FILES['userfile']['tmp_name'];
	    $fileSize = $_FILES['userfile']['size'];
	    $fileType = $_FILES['userfile']['type'];
	    $content = file_get_contents($tmpName);

        // validate input
        $valid = true;
        if (empty($itemName)) {
            $nameError = 'Please enter Item Name';
            $valid = false;
        }
        if (empty($description)) {
            $descriptionError = 'Please enter Description';
            $valid = false;
        }

        if (empty($building)) {
            $descriptionError = 'Please enter building';
            $valid = false;
        }
        $types = array('image/jpeg','image/gif','image/png');
	    if($fileSize > 0) {
		    if(!in_array($fileType, $types)) {
			    $fileType = null;
			    $fileSize = null;
			    $content = null;
			    $pictureError = 'improper file type';
			    $valid=false;
			    
		    }
	    }
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO lostItems (itemName,description,content,contentType,building,person_id) values(?, ?, ?,?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($itemName,$description,$content,$fileType,$building,$person_id));
            Database::disconnect();
            header("Location: lostItems.php");
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
                        <h3>Create a Customer</h3>
                    </div>
             
                    <form class="form-horizontal" action="create.php" method="post" enctype = "multipart/form-data">
                      <div class="control-group <?php echo !empty($itemNameError)?'error':'';?>">
                        <label class="control-label">Item Name</label>
                        <div class="controls">
                            <input name="name" type="text"  placeholder="Item Name" value="<?php echo !empty($itemName)?$itemName:'';?>">
                            <?php if (!empty($itemNameError)): ?>
                                <span class="help-inline"><?php echo $itemNameError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>

                      <div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
                        <label class="control-label">Description</label>
                        <div class="controls">
                            <input name="description" type="text"  placeholder="Item Description" value="<?php echo !empty($description)?$description:'';?>">
                            <?php if (!empty($descriptionError)): ?>
                                <span class="help-inline"><?php echo $descriptionError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Building</label>
                        <div class="controls">
                    <?php
                        $pdo = Database::connect();
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $sql = 'SELECT * FROM buildings ORDER BY id DESC';
                        $select= '<select name="building">';
                        foreach ($pdo->query($sql) as $row){
                              $select.='<option value="'.$row['Name'].'">'.$row['Name'].'</option>';
                          }
                        
                        $select.='</select>';
                        echo $select;
                    ?>
                    <a href="building_create.php" class="btn btn-success">Add New Location</a>
                    </div>
                    </div>
				    <div class="control-group <?php echo !empty($pictureError)?'error':'';?>">
					    <label class="control-label">Picture</label>
					    <div class="controls">
						    <input type="hidden" name="MAX_FILE_SIZE" value="16000000">
						    <input name="userfile" type="file" id="userfile">
						    
					    </div>
				    </div>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Create</button>
                          <a class="btn" href="lostItems.php">Back</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>