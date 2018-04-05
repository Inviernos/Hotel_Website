<?php
 /*
Name: Anthony Golubski-Allen
Date: 9/8/2017
Filename: Login.php
Purpose: Make a login page that checks for an activation link

isCodeInCorrectFormat - Located in util.php

*/
session_start();

require_once "dbconnect.php";
require_once "inc/util.php";
require_once "mail/mail.class.php";

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
	$subject = "Forgotten Password";
	
	
	//user has submit enter
	if (isset($_POST['enter']))
	{
		//get username and password
		$username = trim($_POST['username']);

		
		
		//verify email
		if (spamcheck($username))
		{     
	
			$stmt = $con->prepare("select * from User where email = ?");
			$stmt->execute(array($username));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($row['Email'] == "") 
			{
				$msg = "Email is not in database";
			}
			else
			{
				
				//create a mail object
		        $myMail = new Mail();	
				  
			    $firstName = $row['FirstName'];
				$password = $row['Password'];
			    $body = "Hello " . $firstName . ",<br><br> Here is your password to your hotel account <br><br> Password: " .$password . "<br><br> thanks"; 
				
			    //send mail to user
			    if(($myMail->sendMail($username, $firstName, $subject, $body)))
			    {	
					 //direct to another file to process using query strings
					Header ("Location:Login.php?");	
			
				}
				else
				{
			       $msg = "Email not sent";
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
					  	<a href = "Login.php">Sign In</a><br>
						<a href = "Registration.php">Register Account</a><br>
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
					

				<form action="Forget.php" method="post">
			
				<h2>Remember Password</h2>
				
				<br>
			
				<?php
					print "<font size='+2'>$msg </font>";
				?>
			
				<br> 

				<fieldset>
					<legend>&nbsp;Forgot Password</legend>
		
					&nbsp;Enter Email: 
						<input type="text" maxlength = "50" value="<?php print $username; ?>" name="username" id="username" placeholder="Email"/> <br><br>	
					
						
					&nbsp;<input name="enter" class="btn" type="submit" value="Submit"style="width:100px; height:25px;" /><br><br>
				</fieldset>			

				<br><br>

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