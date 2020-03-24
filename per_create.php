<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_per_create2.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program adds/inserts a new volunteer (table: persons)
 * ---------------------------------------------------------------------------
 */
session_start();
// session_start();
// if(!isset($_SESSION["person_id"])){ // if "user" not set,
	// session_destroy();
	// header('Location: login.php');     // go to login page
	// exit;
// }
	
require '../database/database.php';

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$fnameError = null;
	$lnameError = null;
	$emailError = null;
	$passwordError = null;
	
	// initialize $_POST variables
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$passwordhash = MD5($password);
	

	// validate user input
	$valid = true;
	if (empty($fname)) {
		$fnameError = 'Please enter First Name';
		$valid = false;
	}
	if (empty($lname)) {
		$lnameError = 'Please enter Last Name';
		$valid = false;
	}
	// do not allow 2 records with same email address!
	if (empty($email)) {
		$emailError = 'Please enter valid Email Address (REQUIRED)';
		$valid = false;
	} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$emailError = 'Please enter a valid Email Address';
		$valid = false;
	}

	$pdo = Database::connect();
	$sql = "SELECT * FROM persons";
	foreach($pdo->query($sql) as $row) {

		if($email == $row['email']) {
			$emailError = 'Email has already been registered!';
			$valid = false;
		}
	}
	Database::disconnect();
	
	// email must contain only lower case letters
	if (strcmp(strtolower($email),$email)!=0) {
		$emailError = 'email address can contain only lower case letters';
		$valid = false;
	}

	if (empty($password)) {
		$passwordError = 'Please enter valid Password';
		$valid = false;
	}


	// insert data
	if ($valid) 
	{
		$pdo = Database::connect();
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO persons (fname,lname,email,password) values(?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($fname,$lname,$email,$passwordhash));
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM persons WHERE email = ? AND password = ? LIMIT 1";
		$q = $pdo->prepare($sql);
		$q->execute(array($email,$passwordhash));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		$_SESSION['person_id'] = $data['id'];
		
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
				<h3>Add New Volunteer</h3>
			</div>
	
			<form class="form-horizontal" action="per_create.php" method="post" enctype="multipart/form-data">

				<div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
					<label class="control-label">First Name</label>
					<div class="controls">
						<input name="fname" type="text"  placeholder="First Name" value="<?php echo !empty($fname)?$fname:'';?>">
						<?php if (!empty($fnameError)): ?>
							<span class="help-inline"><?php echo $fnameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($lnameError)?'error':'';?>">
					<label class="control-label">Last Name</label>
					<div class="controls">
						<input name="lname" type="text"  placeholder="Last Name" value="<?php echo !empty($lname)?$lname:'';?>">
						<?php if (!empty($lnameError)): ?>
							<span class="help-inline"><?php echo $lnameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($emailError)?'error':'';?>">
					<label class="control-label">Email</label>
					<div class="controls">
						<input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
						<?php if (!empty($emailError)): ?>
							<span class="help-inline"><?php echo $emailError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
					<label class="control-label">Password</label>
					<div class="controls">
						<input id="password" name="password" type="password"  placeholder="password" value="<?php echo !empty($password)?$password:'';?>">
						<?php if (!empty($passwordError)): ?>
							<span class="help-inline"><?php echo $passwordError;?></span>
						<?php endif;?>
					</div>
				</div>
				
			  
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
					<a class="btn" href="login.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
  </body>
