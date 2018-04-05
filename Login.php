<?php
 /*
Name: Anthony Golubski-Allen
Date: 9/8/2017
Filename: Login.php
Purpose: Make a login page that checks for an activation link

isCodeInCorrectFormat - Located in util.php

*/
session_start();
$_SESSION['timeout'] = time(); //record the time at the user login 
require_once "dbconnect.php";
require_once "inc/util.php";


?>




<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Services - Free Hotel Website Template designed by TemplateMonster</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="description" content="This is a wonderful homepage of the Free Hotel Website Template provided by TemplateMonster."/>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
<script src="maxheight.js" type="text/javascript"></script>
<!--[if lt IE 7]>
	<link href="ie_style.css" rel="stylesheet" type="text/css" />
   <script type="text/javascript" src="ie_png.js"></script>
   <script type="text/javascript">
	   ie_png.fix('.png, #header .row-2, #header .nav li a, #content, .gallery li');
   </script>
<![endif]-->
</head>
<body id="page1" onload="new ElementMaxHeight();">


<?php
	//initialize variables
	$msg = "";
	$username = "";
	$password = "";
	$usre="*";
	$psre="*";
	
	//there is an activation code
	if(isset($_GET['a'])) 
	{

		$code = trim($_GET['a']);
		
		
		//Activation code is in the correct format
		if(isCodeInCorrectFormat($code))
		{
			//update the database
			$stmt = $con->prepare("update User set Activated = 1 where code = ?");
			$stmt->execute(array($code));
			//leave message for user
			$msg = "ACTIVATION SUCCESSFUL";
			
		}
		else
		{
			$msg = "ACTIVATION FAILED";
		}
	} 	
	
	//user has submit enter
	if (isset($_POST['enter']))
	{
		//get username and password
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		
		//Security measure 1, always protect password
		//hash the current passwored entered by the user. It should match with the hashed one in database
		//$pwd = sha1($pwd);

		//now veriy the username and password
		if (spamcheck($username)) //if the email is not a valid format, don't need to continue at all
		{     
					
					
					
			/************************************************************************************************************************************
			 * The following demonstrates providing an OUT variable to stored the retrieved result from the database. 
			 * It is a two step process. First, run the stored procedure to store the value in a variable in the database; Second, get the value from the variable.
			 * The stored procedure is defined as:
			 * Drop procedure if exists SP_COUNT_USER;
			 * Create Procedure SP_COUNT_USER(IN uname VARCHAR(50), IN pwd VARCHAR(60))
			 * Select count(*) as c from REGISTRATION where username = uname and password = pwd; 
			 * The SQL injection does not work if the stored procedure is used.
			 * */ 
					
			//security measure 2: use stored procedures
			$sql = "Call SP_COUNT_USER('".$username."', '".$password."');"; 		
		
			$stmt = $con->query($sql);
			if (!$stmt) 
			{
				$msg = "Username or password incorrect";
			}
			else
			{
				$row = $stmt->fetch(PDO::FETCH_OBJ);
					
				$count = $row->c;

				print "count is ".$count;

				//security measure 3: always use the actual value, don't use $count > 0
				if ($count == 1)
				{
					
					//get email information
					$stmt = $con->prepare("select * from User where Email = ?");
					$stmt->execute(array($username));
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
				
					$uid = $row['UserID'];
				
					$_SESSION['uid'] = $uid;
					$_SESSION['email'] = $username;
			
					
					//see if account is activated
					if($row['Activated'] == 1)
					{
					    print " User authenticated";
						
						$_SESSION['msg'] = "Hello, " . $username; 
				
						Header ("Location:mainAccount.php");	
					}	
					else
					{				
						$msg =  "Need to Activate Account";					
					}
					

				}
				else 
				{
					$msg = "The information entered does not match with the records in our database.";
				}
			}


		}
		else 
		{
			$msg = "Please enter a valid email.";
		}

	}
?>


<div id="main">
<!-- header -->
	<div id="header">
		<div class="row-1">
				<div class="wrapper">
					<div class="logo">
						<h1><a href="index.php">Five Star</a></h1>
						<em>Hotel</em>
						<strong>True Luxury</strong>
					</div>
					<div class="phones">
					    <a href = "Login.php">Sign In</a> <br />
						<a href = "Registration.php">Register Account</a>
					</div>
				</div>
		</div>
	
		<div class="row-2">
	 		<div class="indent">
<!-- header-box begin -->
				<div class="header-box">
					<div class="inner">
						<ul class="nav">
					 		<li><a href="index.php" class="current">Home page</a></li>
							<li><a href="mainAccount.php">Account</a></li>
							<li><a href="gallery.php">Gallery</a></li>
							<li><a href="restaurant.php">Restaurant</a></li>
							<li><a href="booking.php">Booking</a></li>
						</ul>
					</div>
				</div>
<!-- header-box end -->
			</div>
		</div>
	</div><div class="ic">Lovely Flash templates from TemplateMonster!</div>
<!-- content -->
	<div id="content">
		<div class="wrapper">

			<div class="content">
				
				<div class="indent">
					

				<form action="Login.php" method="post">
			
				<h2>Log into Account</h2>
				
				<br>
			
				<?php
					print "<font size='+2'>$msg </font>";
				?>
			
				<br> 

				<fieldset>
					<legend>&nbsp;Login</legend>
		
					&nbsp;Username: <?php print $usre; ?> 
						<input type="text" maxlength = "50" value="<?php print $username; ?>" name="username" id="username" placeholder="Email"/> <br><br>	
					
						
					&nbsp;Password: <?php print $psre; ?> 
						<input type="password" maxlength = "50" value="<?php print $password; ?>" name="password" id="password"  placeholder="Password" /> <br><br>	
					
					&nbsp;<input name="enter" class="btn" type="submit" value="Submit"style="width:100px; height:25px;" /><br><br>
			
				</fieldset>			

				<br><br>


				<a href = "Forget.php">Forgot Password?</a>
			</form>
				</div>
			</div>
		</div>
	</div>

</div>
</body>
</html>



<?php  
include "footer.php";
?>