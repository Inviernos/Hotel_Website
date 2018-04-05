<?php
 /*
Name: Anthony Golubski-Allen
Date: 10/19/2017
Filename: deleteUser.php
Purpose: Make an interactive page that can allow admin 
to delete user from database
*/


session_start();
require_once "dbconnect.php";
require_once "inc/util.php";
require_once "mail/mail.class.php";
require_once "inc/sessionVerify.php";
$_SESSION['timeout'] = time();

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

    $admin = "";
    $adminlink = "";
	$user = "";
	
	//check to see if the user has submitted the form
	if (isset($_POST['enter'])) 
	{
		$user = trim($_POST['User']);
		
		//update the database
		$stmt = $con->prepare("update User set Email = -1, Password = -1, Activated = -1 where Email = ?");
		$stmt->execute(array($user));
		
		$_SESSION['msg'] = "User " . $user . " has been deleted from the database";
		Header ("Location:process.php");
		
	}
	


if(isset($_SESSION['email']))
{
	$stmt = $con->prepare("select * from User where Email = ?");
    $stmt->execute(array($_SESSION['email']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);    
	if($row['Type'] == 2)
	{
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
					

				<form action="deleteUser.php" method="post">
			
				<h1>Delete User</h1>
			
				<br> <br>
					<?php
					
						$list = array();
						$index = 0;
	              	
					
							$stmt = $con->prepare("select * from User where Activated = ?");
							$stmt->execute(array(1));
							$stmt->execute();
							
							while($row = $stmt->fetch(PDO::FETCH_ASSOC))
							{
								
								$list[$index] = $row['Email'];
								$index += 1;
								
							}
							
							
							
							$lists = "";
							for($i = 0; $i <$index; $i++)
							{
								$lists = $lists."<option value=\"".$list[$i]."\">".$list[$i]."</option>" ;
							}
							
							echo "Delete User <br>";
							echo "<select  name = 'User'>";
							echo $lists;
							echo "</select><br><br>";
					?>
				
				<div>
					<input name="enter" class="btn" type="submit" value="Delete User"style="width:100px; height:25px;" /><br><br>

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