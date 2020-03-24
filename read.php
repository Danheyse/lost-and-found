<?php
    session_start();
    if(!isset($_SESSION["person_id"])){ // if "user" not set,
    	session_destroy();
    	header('Location: login.php');     // go to login page
    	exit;
    }
    require '../database/database.php';
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( null==$id ) {
        header("Location: lostItems.php");
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM lostItems where item_id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $itemName = $data['itemName'];
        $description = $data['description'];
        $building = $data['building'];
        Database::disconnect();
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
                        <h3>Read Item</h3>
                    </div>
                      <?php
                      echo '<img width=500 src="data:image/png;base64,'
                                . base64_encode($data['content']) . '"/>';
                        ?>
                    <div class="form-horizontal" >
                      <div class="control-group">
                        <label class="control-label">Name</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $itemName;?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Description</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $description;?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Location</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $building;?>
                            </label>
                        </div>
                      </div>
                        <div class="form-actions">
                          <a class="btn" href="lostItems.php">Back</a>
                       </div>
                     
                      
                    </div>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
