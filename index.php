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
	

	<!-- =========  Start Home Page  ============ -->

	<div data-role="page" class="page" id="home-page">
		<?PHP
			mobile_math_header("Mobile Math Home");
		?>
		  <div role="main" class="ui-content">
			<div id="add-start">
				<div class="quest-header">
					<h2>Welcome to</h2> <h1><em>Mobile Math</em></h1>
					<h4>A simple app to practice basic math operations</h4>	
				</div>
				<div class="quest-box center">
					<!-- Example problem -->
					<div class="ui-grid-a f1-prob icon-container">
						<div class="icon_block ui-block-a">
							<div class="add-icon">
								<a href="#add-page">
									<img alt="Addition Icon" src="img/add_icon.png">
								</a>
							</div>
						</div>
						<div class="icon_block ui-block-b">
							<div class="sub-icon">
								<a href="#sub-page">
									<img alt="Subtraction Icon" src="img/sub_icon.png">
								</a>
							</div>
						</div>
						<div class="icon_block ui-block-a">
							<div class="mul-icon">
								<a href="#mul-page">
									<img alt="Multiplication Icon" src="img/mult_icon.png">
								</a>
							</div>
						</div>
						<div class="icon_block ui-block-b">
							<div class="equ-icon">
								<a href="#div-page">
									<img alt="Equals Icon, but will lead to Division Page" src="img/equ_icon.png">
								</a>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	  	<div data-role="navbar">
	      	<ul>
	        	<li><a href="#settings-page" class="ui-btn ui-icon-gear ui-btn-icon-left">Setting</a></li>
	        	<li><a href="#new-user-page">Users</a></li>
	      	</ul>
	    </div>
	</div>

	<!-- =========  End Home Page  =========== -->

	<!-- =========  Start New User Page ========= -->

	<div data-role='page' class="page" id="new-user-page">
		<?PHP
			mobile_math_header("Users");
		?>
		<div role='main' class="ui-content">
			<!-- Start New User Form -->
			<div class="new-user-form">
				<form action="index.php" method="post">
					<fieldset>
						<legend class="create-user-header">Create a New User</legend>
						<div class="controlgroup">
							<label for="username">Username:</label>
							<input type="text" name="username" id="username" maxlength="50" value="<?PHP echo (isset($_POST['username']) ? trim($_POST['username']) : "")?>" required>
						</div>
						<?PHP echo (isset($errors['username'])) ? $errors['username'] : '' ?>
						<div class="controlgroup">
							<label for="totem">Select Totem:</label>
							<div class="nowrap">
								<div class="img-sel">
									<input type="radio" name="totem" value="test1.jpg"><img src="test1.jpg">	
								</div>
								<div class="img-sel">
									<input type="radio" name="totem" value="test1.jpg"><img src="test1.jpg">	
								</div>
								<div class="img-sel">
									<input type="radio" name="totem" value="test1.jpg"><img src="test1.jpg">	
								</div>
								<div class="img-sel">
									<input type="radio" name="totem" value="test2.jpg"><img src="test2.jpg">	
								</div>
								<div class="img-sel">
									<input type="radio" name="totem" value="test3.jpg"><img src="test3.jpg">	
								</div>	
							</div>
							
							<!-- Need to do the totem thing!! -->	
						</div>
						<?PHP echo (isset($errors['totem'])) ? $errors['totem'] : '' ?>

						<div class="controlgroup">
							<label for="password1">Password:</label>
							<input type="password" name="password1" id="password1" value="<?PHP echo (isset($_POST['password1']) ? $_POST['password1'] : ""); ?>" required>
						</div>
						<?PHP echo (isset($errors['password1'])) ? $errors['password1'] : '' ?>

						<div class="controlgroup">
							<label for="password2">Repeat Password:</label>
							<input type="password" id="password2" name="password2">
						</div>
						<?PHP echo (isset($errors['password2'])) ? $errors['password2'] : '' ?>

						<div class="controlgroup">
							<input type="submit" name="new-user-submit" value="Create_User">
						</div>
						<?PHP echo (isset($errors['database'])) ? $errors['database'] : '' ?>
						<?PHP echo (!empty($success)) ? $success : '' ?>
					</fieldset>
				</form>
			</div>
			
			
		</div>
		<div data-role="navbar">
	      	<ul>
	        	<li><a href="#settings-page" class="ui-btn ui-icon-gear ui-btn-icon-left">Setting</a></li>
	        	<li><a href="#new-user-page">Users</a></li>
	      	</ul>
    	</div>
	</div>

	<!-- ====  End new User Page ===== -->



	
	<!-- ===============  Start Settings Page  ============= -->
	
	<!-- Addtion, Subtraction, Mult -->
	<!-- A: max arg, S: Max arg, M: Table #, Max arg, D: Table#, Max arg-->
	<!-- Addition Landing -->
	<div data-role="page" class="page" id="settings-page">
		
	<?PHP
		mobile_math_header("Settings");
	?>
		<!-- Add  restore default buttons for each setting operation -->

	    <div data-role='main' class="ui-content">
		    <div class="quest-header">
				<div class="quest-box">
					<div class="ui-grid-b">
						<div class="legend-like">
							<a href="#myPopup" data-rel="popup" data-transition="flip"
							 class="ui-btn ui-btn-inline ui-icon-info  ui-btn-icon-notext ui-corner-all ui-shadow ">Info</a>
							<div data-role="popup" id="myPopup">
								<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
							    <p>Addition: Positive Integers, 1-50</p>
							    <p>Subtraction: Positive Integers, 1-50</p>
							  	<p>Multiplication:</p>
							  	<p>Tables: 1-12</p>
							  	<p>Multiplier: 1-9</p>
							  	<p>Tables: 1-12</p>
							  	<p>Dividend: 1-9</p>
							</div>
							<h3 >Addition</h3>	
						</div>

						<div class="ui-block-a">
							<p title="Positive Integers - 1 to 50">Max Value:</p>	
						</div>
						<div class="ui-block-b">
							<input id="a-max" type="number" min="1" max="50">	
						</div>
						<div class="ui-block-c">
							<a id="a-set" class="ui-btn ui-btn-inline ui-btn-b">Set</a>
						</div>
					</div>
					<div class="ui-grid-b">
						<h3 class="legend-like">Subtraction</h3>
						<div class="ui-block-a">
							<p>Max Value:</p>	
						</div>
						<div class="ui-block-b">
							<input id="s-max" type="number" min="1" max="50">	
						</div>
						<div class="ui-block-c">
							<a id="s-set" class="ui-btn ui-btn-inline ui-btn-b">Set</a>
						</div>
					</div>
					<div class="ui-grid-b">




						<h3 class="legend-like">Multiplication</h3>
						<div class="ui-block-a arg">
							<p>Mult Table:</p>	
						</div>
						<div class="ui-block-b arg">
							<input id="m-table" type="number" max="12" min="1">	
						</div>
						<div class="ui-block-c">
							<a id="m-t-set" class="ui-btn ui-btn-inline ui-btn-b">Set</a>
						</div>





					</div>
					<div class="ui-grid-b">
						<div class="ui-block-a arg">
							<p>Max Multiplier:</p>	
						</div>
						<div class="ui-block-b arg">
							<input id="m-mult" type="number" max="9" min="1">	
						</div>
						<div class="ui-block-c">
							<a id="m-m-set" class="ui-btn ui-btn-inline ui-btn-b">Set</a>
						</div>
					</div>
					<div class="ui-grid-b">
						<h3 class="legend-like">Division</h3>
						<div class="ui-block-a arg">
							<p>Div Table:</p>	
						</div>
						<div class="ui-block-b arg">
							<input id="d-table" type="number" max="13" min="1">	
						</div>
						<div class="ui-block-c">
							<a id="d-t-set" class="ui-btn ui-btn-inline ui-btn-b">Set</a>
						</div>
					</div>
					<div class="ui-grid-b">
						<div class="ui-block-a arg">
							<p>Max Dividend:</p>	
						</div>
						<div class="ui-block-b arg">
							<input id="d-div" type="number" max="100" min="10">	
						</div>
						<div class="ui-block-c">
							<a id="d-m-set" class="ui-btn ui-btn-inline ui-btn-b">Set</a>
						</div>
					</div>
					<div class="ui-grid-b">
						<h3 class="legend-like">Timer</h3>
						<div class="ui-block-a arg">
							<p>Start Time:</p>	
						</div>
						<div class="ui-block-b arg" style="font-size: .5em;">
							<select name="t-max" id="t-max">
								<option value="5">5 Seconds</option>
								<option value="30">30 Seconds</option>
								<option value="60">1 Minute</option>
								<option value="120">2 Minutes</option>
								<option value="180">3 Minutes</option>
								<option value="240">4 Minutes</option>
								<option value="300">5 Minutes</option>
							</select>	
						</div>
						<div class="ui-block-c">
							<a id="t-set" class="ui-btn ui-btn-inline ui-btn-b">Set</a>
						</div>
					</div>
				</div>	
			</div>
		</div>
		<div data-role='footer'>
			<div data-role="navbar">
		      	<ul>
		        	<li><a href="#settings-page" class="ui-btn ui-icon-gear ui-btn-icon-left">Setting</a></li>
		        	<li><a href="#new-user-page">Users</a></li>
		      	</ul>
		    </div>	
		</div>
	</div>
	
	<!-- =====  End Settings Page ===== -->
	
	<!-- Settings instructions dialog -->

	<div data-role="page" data-dialog="true" id="setting-inst">
	  <div data-role="main" class="ui-content">
	    <a href="#pageone">Go to Page One</a>
	  </div>
	</div>


	<!-- ===============  Start Addition Page  ============= -->

	<div data-role="page" class="page" id="add-page">
		<?PHP
			mobile_math_header("Mobile Addition");
		?>
		<div role="main" class="ui-content">
			<div id="add-quest">
			<?PHP
				math_controls_top("add");
			?>
				<div class="quest-box">
					<!-- Q1 -->
					<div id="a-p1" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a arg">
							<input id="a-1-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="a-1-op"><p>+</p></div>	
						</div>
						<div class="ui-block-c arg">
							<input id="a-1-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="a-1-e"><p>=</p></div>	
						</div>
						
						<div class="ui-block-e">
							<input class="answer" id="a-1-ans" type="number" name="add-ans" value="">
						</div>
					</div>

					<!-- Q2 -->
					<div id="a-p2" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a">
							<input id="a-2-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="a-2-op"><p>+</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="a-2-a2" readonly>
						</div>
						<div class="ui-block-d center">
							<div id="a-2-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="a-2-ans" type="number" name="add-ans" value="">
						</div>
					</div>

					<!-- Q3 -->
					<div id="a-p3" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a">
							<input id="a-3-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="a-3-op"><p>+</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="a-3-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="a-3-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="a-3-ans" type="number" name="add-ans" value="">
						</div>
					</div>
					<!-- Q4 -->
					<div id="a-p4" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a">
							<input id="a-4-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="a-4-op"><p>+</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="a-4-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="a-4-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="a-4-ans" type="number" name="add-ans" value="">
						</div>
					</div>
					
					<div class="">
						<div class="time-disp">
							<label for="a-time">Time:</label>
							<input id="a-time" readonly>
						</div>
						<div class="score-disp ">
							<label for='a-score'>Score:</label>
							<input id='a-score' readonly>
						</div>
					</div>	
					
				</div>
				<div class="center" data-role="controlgroup" data-mini="true">
					<a href="" id="add-check" class="ui-btn ui-btn-b ui-btn-inline">Next...</a>	
				</div>
					
			</div>
		</div>
		<div data-role="navbar">
	      	<ul>
	        	<li><a href="#settings-page" class="ui-btn ui-icon-gear ui-btn-icon-left">Setting</a></li>
	        	<li><a href="#new-user-page">Users</a></li>
	      	</ul>
	    </div>

	    <!-- Add Success Popup -->
		<div id="addSuccessPopup" data-role="popup" data-transition='pop' class="center">
			<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
			<h2>Congratulations!!!</h2>
			<p>You finished before the timer ran out!</p>
			<h3>Good Job on you Addition!</h3>
		</div>

		<!-- Add Fail Popup -->
		<div id="addFailPopup" data-role="popup" class="center">
			<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
			<h2>Sorry!!!</h2>
			<p>You didn't finished before the timer ran out!</p>
			<h3>Try Try Again...</h3>
		</div>

	</div>

	<!-- ========  End Addition Page ======== -->




	<!-- =============== Start Multiplication Page ============ -->

	<div data-role="page" class="page" id="mul-page">
		<?PHP
			mobile_math_header("Mobile Multiplication");
		?>

		<div role="main" class="ui-content">
			<div id="mul-quest">
			<?PHP
				math_controls_top("mul");
			?>
				<div class="quest-box">
					<!-- Q1 -->
					<div id="m-p1" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a arg">
							<input id="m-1-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="m-1-op"><p>x</p></div>	
						</div>
						<div class="ui-block-c arg">
							<input id="m-1-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="m-1-e"><p>=</p></div>	
						</div>
						
						<div class="ui-block-e">
							<input class="answer" id="m-1-ans" type="number" name="mul-ans" value="">
						</div>
					</div>

					<!-- Q2 -->
					<div id="m-p2" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a">
							<input id="m-2-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="m-2-op"><p>x</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="m-2-a2" readonly>
						</div>
						<div class="ui-block-d center">
							<div id="m-2-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="m-2-ans" type="number" name="mul-ans" value="">
						</div>
					</div>

					<!-- Q3 -->
					<div id="m-p3" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a">
							<input id="m-3-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="m-3-op"><p>x</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="m-3-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="m-3-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="m-3-ans" type="number" name="mul-ans" value="">
						</div>
					</div>
					<!-- Q4 -->
					<div id="m-p4" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a">
							<input id="m-4-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="m-4-op"><p>x</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="m-4-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="m-4-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="m-4-ans" type="number" name="mul-ans" value="">
						</div>
					</div>
					
					<div class="">
						<div class="time-disp">
							<label for="m-time">Time:</label>
							<input id="m-time" readonly>
						</div>
						<div class="score-disp ">
							<label for='m-score'>Score:</label>
							<input id='m-score' readonly>
						</div>
					</div>	
					
				</div>
				<div class="center" data-role="controlgroup" data-mini="true">
					<a href="" id="mul-check" class="ui-btn ui-btn-b ui-btn-inline">Next...</a>	
				</div>

			</div>
		</div>

		<!-- bottom nav -->
		<div data-role="navbar">
	      	<ul>
	        	<li><a href="#settings-page" class="ui-btn ui-icon-gear ui-btn-icon-left">Setting</a></li>
	        	<li><a href="#new-user-page">Users</a></li>
	      	</ul>
	    </div>

	    <!-- Popups -->

	    <!-- Add Success Popup -->
		<div id="mulSuccessPopup" data-role="popup" data-transition='pop' class="center">
			<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
			<h2>Congratulations!!!</h2>
			<p>You finished before the timer ran out!</p>
			<h3>Good Job on you Multiplication!</h3>
		</div>

		<!-- Add Fail Popup -->
		<div id="mulFailPopup" data-role="popup" class="center">
			<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
			<h2>Sorry!!!</h2>
			<p>You didn't finished before the timer ran out!</p>
			<h3>Try Try Again...</h3>
		</div>

	</div>

	<!-- ========  End Multiplication Page ===== -->





	<!-- ===============  Start Subtraction Page  ============= -->

	<div data-role="page" class="page" id="sub-page">
		<?PHP
			mobile_math_header("Mobile Subtraction");
		?>
		<!-- Start *quest* Here -->

		<div role="main" class="ui-content">
			
			<div id="sub-quest">
			<?PHP
				math_controls_top("sub");
			?>	
				<div class="quest-box">
					<!-- Q1 -->
					<div id="s-p1" class="ui-grid-d f1-prob s-input">
						<div class="ui-block-a arg">
							<input id="s-1-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="s-1-op"><p>-</p></div>	
						</div>
						<div class="ui-block-c arg">
							<input id="s-1-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="s-1-e"><p>=</p></div>	
						</div>
						
						<div class="ui-block-e">
							<input class="answer" id="s-1-ans" type="number" name="sub-ans" value="">
						</div>
					</div>

					<!-- Q2 -->
					<div id="s-p2" class="ui-grid-d f1-prob s-input">
						<div class="ui-block-a">
							<input id="s-2-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="s-2-op"><p>-</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="s-2-a2" readonly>
						</div>
						<div class="ui-block-d center">
							<div id="s-2-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="s-2-ans" type="number" name="sub-ans" value="">
						</div>
					</div>

					<!-- Q3 -->
					<div id="s-p3" class="ui-grid-d f1-prob s-input">
						<div class="ui-block-a">
							<input id="s-3-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="s-3-op"><p>-</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="s-3-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="s-3-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="s-3-ans" type="number" name="sub-ans" value="">
						</div>
					</div>
					<!-- Q4 -->
					<div id="s-p4" class="ui-grid-d f1-prob s-input">
						<div class="ui-block-a">
							<input id="s-4-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="s-4-op"><p>-</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="s-4-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="s-4-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="s-4-ans" type="number" name="sub-ans" value="">
						</div>
					</div>
					
					<div class="">
						<div class="time-disp">
							<label for="s-time">Time:</label>
							<input id="s-time" readonly>
						</div>
						<div class="score-disp ">
							<label for='s-score'>Score:</label>
							<input id='s-score' readonly>
						</div>
					</div>	
					
				</div>
				<div class="center" data-role="controlgroup" data-mini="true">
					<a href="" id="sub-check" class="ui-btn ui-btn-b ui-btn-inline">Next...</a>	
				</div>
					
			</div>
		</div>

		<!-- bottom nav -->
		<div data-role="navbar">
	      	<ul>
	        	<li><a href="#settings-page" class="ui-btn ui-icon-gear ui-btn-icon-left">Setting</a></li>
	        	<li><a href="#new-user-page">Users</a></li>
	      	</ul>
	    </div>

		<!-- Popups -->

	    <!-- Add Success Popup -->
		<div id="subSuccessPopup" data-role="popup" data-transition='pop' class="center">
			<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
			<h2>Congratulations!!!</h2>
			<p>You finished before the timer ran out!</p>
			<h3>Good Job on you Subtraction!</h3>
		</div>

		<!-- Add Fail Popup -->
		<div id="subFailPopup" data-role="popup" class="center">
			<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
			<h2>Sorry!!!</h2>
			<p>You didn't finished before the timer ran out!</p>
			<h3>Try Try Again...</h3>
		</div>

	</div>

	<!-- ===========  End Subtraction Page ============ -->



	<!-- =============== Start Division Page ============ -->

	<div data-role="page" class="page" id="div-page">
		<?PHP
			mobile_math_header("Mobile Division");
		?>

		<div role="main" class="ui-content">
			<div id="div-quest">
			<?PHP
				math_controls_top("div");
			?>
				<div class="quest-box">
					<!-- Q1 -->
					<div id="d-p1" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a arg">
							<input id="d-1-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="d-1-op"><p>&#247;</p></div>	
						</div>
						<div class="ui-block-c arg">
							<input id="d-1-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="d-1-e"><p>=</p></div>	
						</div>
						
						<div class="ui-block-e">
							<input class="answer" id="d-1-ans" type="number" name="div-ans" value="">
						</div>
					</div>

					<!-- Q2 -->
					<div id="d-p2" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a">
							<input id="d-2-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="d-2-op"><p>&#247;</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="d-2-a2" readonly>
						</div>
						<div class="ui-block-d center">
							<div id="d-2-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="d-2-ans" type="number" name="div-ans" value="">
						</div>
					</div>

					<!-- Q3 -->
					<div id="d-p3" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a">
							<input id="d-3-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="d-3-op"><p>&#247;</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="d-3-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="d-3-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="d-3-ans" type="number" name="div-ans" value="">
						</div>
					</div>
					<!-- Q4 -->
					<div id="d-p4" class="ui-grid-d f1-prob a-input">
						<div class="ui-block-a">
							<input id="d-4-a1" readonly>	
						</div>
						<div class="ui-block-b center">
							<div id="d-4-op"><p>&#247;</p></div>	
						</div>
						<div class="ui-block-c">
							<input id="d-4-a2" readonly>	
						</div>
						<div class="ui-block-d center">
							<div id="d-4-e"><p>=</p></div>	
						</div>

						<div class="ui-block-e">
							<input class="answer" id="d-4-ans" type="number" name="div-ans" value="">
						</div>
					</div>
					
					<div>
						<div class="time-disp">
							<label for="d-time">Time:</label>
							<input id="d-time" readonly>
						</div>
						<div class="score-disp ">
							<label for='d-score'>Score:</label>
							<input id='d-score' readonly>
						</div>
					</div>	
					
				</div>
				<div class="center" data-role="controlgroup" data-mini="true">
					<a href="" id="div-check" class="ui-btn ui-btn-b ui-btn-inline">Next...</a>	
				</div>
			</div>
		</div>

		<!-- bottom nav -->
		<div data-role="navbar">
	      	<ul>
	        	<li><a href="#settings-page" class="ui-btn ui-icon-gear ui-btn-icon-left">Setting</a></li>
	        	<li><a href="#new-user-page">Users</a></li>
	      	</ul>
	    </div>

	    <!-- Popups -->

	    <!-- Add Success Popup -->
		<div id="divSuccessPopup" data-role="popup" data-transition='pop' class="center">
			<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
			<h2>Congratulations!!!</h2>
			<p>You finished before the timer ran out!</p>
			<h3>Good Job on you Division!</h3>
		</div>

		<!-- Add Fail Popup -->
		<div id="divFailPopup" data-role="popup" class="center">
			<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
			<h2>Sorry!!!</h2>
			<p>You didn't finished before the timer ran out!</p>
			<h3>Try Try Again...</h3>
		</div>

	</div>

	<!-- ========  End Division Page ===== -->
	
<?PHP
include "audio_content.php";
?>	
	
</body>
</html>