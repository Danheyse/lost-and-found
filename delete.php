<?php
    require '../database/database.php';
    session_start();
    if(!isset($_SESSION["person_id"])){ // if "user" not set,
	    session_destroy();
	    header('Location: login.php');     // go to login page
	    exit;
    }
    $item_id= 0;
     
    if ( !empty($_GET['id'])) {
        $item_id= $_REQUEST['id'];
    }
     
    if ( !empty($_POST)) {
        // keep track post values
        $item_id= $_POST['id'];
         
        // delete data
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM lostItems  WHERE item_id= ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($item_id));
        Database::disconnect();
        header("Location: lostItems.php");
         
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
                        <h3>Delete a Customer</h3>
                    </div>
                     
                    <form class="form-horizontal" action="delete.php" method="post">
                      <input type="hidden" name="id" value="<?php echo $item_id;?>"/>
                      <p class="alert alert-error">Are you sure to delete ?</p>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-danger">Yes</button>
                          <a class="btn" href="lostItems.php">No</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>