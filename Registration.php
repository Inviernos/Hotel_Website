<?php
 /*
Name: Anthony Golubski-Allen
Date: 9/8/2017
Filename: Registration.php
Purpose: Make an interactive registration page that gets the user's 
	 information and display it on the next page.
*/


session_start();
$_SESSION['timeout'] = time();
require_once "dbconnect.php";
require_once "inc/util.php";
require_once "mail/mail.class.php";

require_once "header.php";

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
	$firstName = "";
	$lastName = "";
	$password = "";
	$confirmedPassword = "";
	$em = "";
	$confirmedEmail = "";
	$phoneNumber = "";
	$description = "no description";
	$profImage = '<img src="profile/ghost.png" alt="Profile Picture"';
	$agreed = false;
	$msg = "";
	$fnre="*";
	$lnre="*";
	$emre="*";
	$cemre="*";
	$psre="*";
	$pnre="*";
	$cpsre= "*";
	$agreedre = "*";
	$subject = "Account Activation";
	$body = "";
	$code = "";
	$codeLength = 50;
	$type = 1;
	$activated = 0;
    $admin = "";
    $adminlink = "";
		
	//check to see if the user has submitted the form
	if (isset($_POST['enter'])) 
	{
		//trim the user input
		$firstName = trim($_POST['firstName']);
		$lastName = trim($_POST['lastName']);
		$password = trim($_POST['password']);
		$confirmedPassword = trim($_POST['confirmedPassword']);
		$phoneNumber = trim($_POST['phoneNumber']);
		
		
		//first name is blank
		if($firstName == "")
		{
		 	$fnre = '<span style="color:red">*</span>';
		}

		//last name is blank
		if($lastName == "")
		{
		 	$lnre = '<span style="color:red">*</span>';
		}
		
		//phone number is blank
		if($phoneNumber == "")
		{
			$pnre = '<span style="color:red">*</span>';
		}
		
		//password field is blank
		if($password == "")
		{
		   $psre = '<span style="color:red">*</span>';
		}
		
		//confirmed password field is blank
		if($confirmedPassword == "")
		{
		   $cpsre = '<span style="color:red">*</span>';
		}

		//The two password fields do not have the same value
		if($password != $confirmedPassword)
		{
			$psre = '<span style="color:red">*</span>';
			$cpsre = '<span style="color:red">*</span>';
		}
		
		//The password does not have at least 10 characters with letters and numbers
		if(!pwdValidate($password))
		{
			$psre = '<span style="color:red">*</span>';
			$cpsre = '<span style="color:red">*</span>';
		}
	
		//Email field is not in the correct format
		if (!filter_input(INPUT_POST, 'email',FILTER_VALIDATE_EMAIL)) 
		{
			$emre = '<span style="color:red">*</span>';
		}
		else 
		{
			$em = trim($_POST['email']);
		}
		
		//Confirm Email Field is not in the correct format
		if(!filter_input(INPUT_POST, 'confirmedEmail',FILTER_VALIDATE_EMAIL))
		{
			$cemre = '<span style="color:red">*</span>';
		}
		else
		{
			$confirmedEmail = trim($_POST['confirmedEmail']);
		}		
	
		//The two email fields do not have the same value
		if($em != $confirmedEmail)
		{
			$emre = '<span style="color:red">*</span>';
			$cemre = '<span style="color:red">*</span>';
		}


		$agreed = isset($_POST['terms']);
		
		if(isset($_POST['type']))
		{
			$type = trim($_POST['type']);
		}
		
		//User has not agreed to terms and agreement
		if(!$agreed)
		{
			$agreedre =  '<span style="color:red">*</span>';
		}	
		
		
		
		
			
		//User has entered invalid data
		if (($fnre!="*") || ($lnre != "*") || ($pnre != "*") || ($emre != "*") || ($psre != "*") || ($cpsre != "*") || ($cemre != "*") || ($agreedre != "*"))				
		{	
			$msg = "<br /><span style=\"color:red\">Please enter valid data.</span><br />";
		}
		else 
		{	
			
			//check if email is in database
			$sql = "Call SP_COUNT_EMAIL('".$username."');"; 		

			$stmt = $con->query($sql);
		
			$row = $stmt->fetch(PDO::FETCH_OBJ);
					
			$count = $row->c;

		
			if ($count == 1) 
			{
				$msg = "<br /><span style=\"color:red\">Username already exist.</span><br />";
			}
			else
			{
				//create a mail object
				$myMail = new Mail();
				
				//create a random code of 50 characters of letters and digits 
				$code = randomCodeGenerator($codeLength);

				//create a body message 
				$body = "Hello " . $firstName . ",<br><br> Please click the following link below to activate your account <br> http://corsair.cs.iupui.edu:21091/Final/Login.php?a=" .$code . "<br><br> thanks"; 
				
				//send mail to user
				if(($myMail->sendMail($em, $firstName, $subject, $body)))
				{	
					//use stored procedures
					//ql2 = "Call SP_INSERT_User('".$firstName."', '".$lastName."', '".$em."', '".$password."','".$phoneNumber."', '".$code."',".$activated.", ".$type.");"; 		
					//$stmt2 = $con->query($sql2);
					$stmt = $con->prepare("INSERT INTO User (FirstName, LastName, Email, Password, Phone, Code, Activated, Type) VALUES (?, ?, ?, ?, ?, ? , ? , ?)");
					$stmt->execute(array($firstName, $lastName, $em, $password, $phoneNumber, $code, $activated, $type));
					 
					//echo "";
					//direct to another file to process using query strings
					Header ("Location:confirmation.php?");		
				}
				else
				{
					$msg = "Email not sent";
				}
			}
			
			
									
		}
	
}

if(isset($_SESSION['email']))
{
	$stmt = $con->prepare("select * from User where Email = ?");
	

    $stmt->execute(array($_SESSION['email']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);    
	if($row['Type'] == 2)
	{
		$admin = '&nbsp;User Type: <select name = "type"> <option value = "1" selected>User</option><option value = "2">Administrator</option></select> <br><br>';
	    $adminlink = '<a href = "deleteUser.php">Delete Users Account</a> <br />';
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
					<?php
					  print $links;
					?>
						<a href = "Registration.php">Register Account</a> <br>
					<?php
					  print $adminlink;
					?>
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
					

				<form action="Registration.php" method="post">
			
				<h1>User Registration</h1>
			
				<?php
					print $msg;
				?>
			
				<br> <br>

			
			
				First Name: <?php print $fnre; ?>
					 <input type="text" maxlength = "50" value="<?php print $firstName; ?>" name="firstName" id="firstName"  placeholder="First Name" /> <br><br>
				Last Name: <?php print $lnre; ?>
					<input type="text" maxlength = "50" value="<?php print $lastName; ?>" name="lastName" id="lastName"  placeholder="Last Name" /> <br><br>
				Phone Number: <?php print $pnre; ?>
					<input type="tel" maxlength = "50" value="<?php print $phoneNumber; ?>" name="phoneNumber" id="phoneNumber"  placeholder="Phone Number" /> <br><br>					
				Email: <?php print $emre; ?>
					<input type="email" maxlength = "50" value="<?php print $em; ?>" name="email" id="email" placeholder="Email"/> <br><br>	
				Confirm Email: <?php print $cemre; ?>
					<input type="email" maxlength = "50" value="<?php print $confirmedEmail; ?>" name="confirmedEmail" id="confirmedEmail" placeholder="Confirm Email"   /> <br><br>			
				Password must contain at least 10 characters with letters and digits <br><br>
				Password: <?php print $psre; ?> 
					<input type="password" maxlength = "50" value="<?php print $password; ?>" name="password" id="password"  placeholder="Password" /> <br><br>	
				Confirm Password: <?php print $cpsre; ?>
					<input type="password" maxlength = "50" value="<?php print $confirmedPassword; ?>" name="confirmedPassword" id="confirmedPassword"  placeholder="Confirmed Password" /> <br><br>	
				
	
				<?php
				    echo $admin;
				?>
				
				
				<div>
				
		
				
					
				    <input type="checkbox" name="terms" value="terms" /><?php print $agreedre; ?> Agree to terms and policies <br><br>
					<input name="enter" class="btn" type="submit" value="Submit"style="width:100px; height:25px;" /><br><br>

			

				</div>

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