<?php
session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
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
            <div class="row">
                <h3>Lost Items</h3>
            </div>
            <div class="row">
                <p>
                    <a href="create.php" class="btn btn-success">Add New Lost Item</a>
                    <a href="per_update.php" class="btn btn-success">Update Account Info</a>
                    <a href="per_delete.php" class="btn btn-success">Delete Account</a>
                    <a href="login.php" class="btn btn-success">Logout</a>
                </p>
                 
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Item Name</th>
                          <th>Description</th>
                          <th>Picture</th>
                          <th>Location</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                       require '../database/database.php';
                       $pdo = Database::connect();
                       $sql = 'SELECT * FROM lostItems ORDER BY item_id DESC';
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td>'. $row['itemName'] . '</td>';
                                echo '<td>'. $row['description'] . '</td>';
                                echo '<td>'
                                . '<img width=100 src="data:image/'.$row['contentType'].';base64,'
                                . base64_encode($row['content']) . '"/>' .  '</td>';
                                echo '<td>'. $row['building'] . '</td>';
                                echo '<td width=250>';
                                echo '<a class="btn" href="read.php?id='.$row['item_id'].'">View</a>';
                                echo ' ';
                                echo '<a class="btn btn-success" href="update.php?id='.$row['item_id'].'">Update</a>';
                                echo ' ';
                                echo '<a class="btn btn-danger" href="delete.php?id='.$row['item_id'].'">Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                       }
                       Database::disconnect();
                      ?>
                      </tbody>
                </table>
        </div>
    </div> <!-- /container -->
  </body>
</html>
