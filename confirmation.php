<?php
 /*
Name: Anthony Golubski-Allen
Date: 10/19/2017
Filename: confirmation.php
Purpose: tell user that email has been sent
*/

session_start();
require_once "header.php";

require_once "dbconnect.php";
  
$_SESSION['timeout'] = time();
  
$admin = "";
	
if(isset($_SESSION['email']))
{
	$stmt = $con->prepare("select * from User where Email = ?");
    $stmt->execute(array($_SESSION['email']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);    
	if($row['Type'] == 2)
	{
		$admin = '<a href = "deleteUser.php">Delete Users Account</a> <br />';
	}

}
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
						<a href = "Registration.php">Register Account</a><br>
						  <?php
						   print $admin;
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
					

						
					<h2>User Registration Confirmation</h2>
				
					<br><br>
			
					<h2>Activation code has been sent to your email address.</h2>
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


	
			
		