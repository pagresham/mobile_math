<!DOCTYPE html>
<html lang="en">
<head>
	<title>MathApp</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />

	<!-- Download link -->
	<!-- <link rel="stylesheet" type="text/css" href="jMobile/jquery.mobile-1.4.5.css"> -->

	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<!-- <script type="text/javascript" src="jMobile/jquery-3.2.1.js"></script> -->
	
	<!-- Download link -->
	<!-- <script type="text/javascript" src="jMobile/jquery-1.12.4.js"></script> -->
	<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	
	<!-- Download link -->
	<!-- <script type="text/javascript" src="jMobile/jquery.mobile-1.4.5.js"></script> -->
	<script type="text/javascript" src="app.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<?PHP
session_start();
require("db_helpers/config.php");
require("db_helpers/connect.php");
include "mobile_math_helpers.php";
$username = $password1 = $password2 = $totem = $hashedPassword = $success = "";
$errors = array();
$errorCount = 0;


if(isset($_POST['new-user-submit'])) {

	if(!empty($_POST['username'])) {
		$username = trim($_POST['username']);
		if(!strlen(trim($username)) == 0) {
			if(!preg_match("/^[a-zA-Z0-9]{6,45}/", $username)){
				$errors['username'] = "<small class='errorText'>Incorrect Username Format</small>";
			}
			
		}
		else {
			$errors['username'] = "<small class='errorText'>Username cannot be blank</small>";
		}
	}
	else {
			$errors['username'] = "<small class='errorText'>Username is a required field</small>";
	}

	// Validate Totem //
	if(isset($_POST['totem'])) {
		$totem = $_POST['totem'];
		if(!preg_match("/^[a-zA-Z .]{1,50}/", $totem)) {
			$errors['totem'] = "<small class='errorText'>Please enter an official toten for you account.</small>";
		}	
	}
	else {
		$errors['totem'] = "<small class='errorText'>The totem is a required field for your account.</small>";
	}

	// Validate 1st PW
  	if (!empty($_POST['password1'])) {
    	$password1 = $_POST['password1'];
    	if (strlen(trim($password1)) === 0) {
      		$errors['password1'] = "<small class='errorText'>Please enter a valid password</small>";
    	}
    	else  if (!preg_match ("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password1))  {
      		//print $password1;
      		$errors['password1'] = "<small class='errorText'>Password must contain at least 8 characters and include 1 lower case, 1 upper case, 1 number, and 1 special character</small>";
    	}
  	}
  	else {
      $errors['password1'] = "<small class='errorText'>Password is a required field</small>";
  	}
  	// Password 2
  	if (isset($_POST['password2'])) {
    	$password2 = $_POST['password2'];
    	if($password1 != $password2) {
      		$errors['password2'] = "<small class='errorText'>Passwords do not match</small>";
    	}
  	}
  	else {
    	$errors['password2'] = "<small class='errorText'>Plese re-enter you password</small>";
  	}

  	$errorCount = count($errors);
  	if($errorCount > 0) {
  		print "<small class='errorText'>There are errors. Please make corrections and try again</small>";
    	$validImputs = false;
  	}
  	else {
  		// No Errors //  
 		// Start with checking if user name exists already //
 		
 		$user_query = "SELECT u_name FROM users
 						where u_name = '$username'";
 		$user_query_result = $db->query($user_query);

 		if(!$user_query_result) { // Error with Select Statement
 			// die("Connection Terminated - Username Select Error:".$db->mysqli_error);
 			die("Connection Terminated - Username Select Error:".mysqli_error($db));

  		}
  		else {
  			if($user_query_result->num_rows > 0) { // Username already in use
  				$errors['username'] = "<small class='errorText'>Sorry, that username is already taken.</small>";
  			}
  			else if($user_query_result->num_rows == 0) { // Valid new Username
  				$hashedPassword = password_hash($password1, 1);
  				$username = addslashes($username);
  				$insert_query = "INSERT INTO users
  								(completions, u_name, add_rnd, sub_rnd, mul_rnd, div_rnd, totem, password)
  									values(0, '$username', 0, 0, 0, 0, '$totem', '$hashedPassword')";
  	
  				$result = $db->query($insert_query);

  				if(!$result) {
  					// die("Connection Terminated at User Insert: " . $db->error);
  					$errors['database'] = "Unable to process database request: ".$db->error;
  					// header("Location: index.php");
  				}
  				else {
  					// header("Location: index.php");
  					$success = "Successfully created user: ".$username;
  				}
  			}
  		}
 	}
}

?>


<body>