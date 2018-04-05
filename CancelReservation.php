<?php

 /*
Name: Anthony Golubski-Allen
Date: 9/8/2017
Filename: CancelReservation.php
Purpose: Make an interactive page that can allow admin or user to cancel reservation
*/
  session_start();
 
  require_once "header.php";

  //if this is a page that requires login always perform this session verification
  require_once "inc/sessionVerify.php";
  $_SESSION['timeout'] = time();

  require_once "dbconnect.php";
  
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


   $stmt = $con->prepare("select * from User where Email = ?");
   $stmt->execute(array($_SESSION['email']));
   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   $admin = false;
  
   $userID = "";
   $roomNum = "";
   $check = false;
   $adminlink = "";

   
   $userID = $row['UserID']; 
  
   
   //user has submit enter
   if (isset($_POST['enter']))
   {
	    $check = true;
	    $Customer = trim($_POST['rooming']);
		
		
		$stmt3 = $con->prepare("select * from Customer where CustomerID = ?");
		$stmt3->execute(array((int)$Customer));
		$row2 = $stmt3->fetch(PDO::FETCH_ASSOC);
		
		$stmt4 = $con->prepare("select * from Room where RoomID = ?");
		$stmt4->execute(array($row2['Room']));
		$row3 = $stmt4->fetch(PDO::FETCH_ASSOC);
		
		$_SESSION['msg'] = "Room # " . $row3['RoomNum'] . " has been canceled";
		
		$stmt2 = $con->prepare("update Customer set canceled = 1 where CustomerID = ?");
		$stmt2->execute(array((int)$Customer));
		
		Header ("Location:process.php");
		
   }
   
   if($row['Type'] == 2)
   {
	   $admin = true;
	   $adminlink = '<a href = "deleteUser.php">Delete Users Account</a> <br />';
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
						<a href = "Registration.php">Register Account</a><br>
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
					 		<li><a href="index.php">Home page</a></li>
							<li><a href="mainAccount.php">Account</a></li>
							<li><a href="gallery.php">Gallery</a></li>
							<li><a href="restaurant.php">Restaurant</a></li>
							<li><a href="booking.php" class = "current">Booking</a></li>
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
			
			 
				<form action="CancelReservation.php" method="post">
				 
				 <?php
				 
						$list = array();
						$customerID = array();
						$index = 0;
						
						if($admin)
						{
							$stmt = $con->prepare("select * from Customer where ? < Start_Date AND canceled = ?");
							$stmt->execute(array(date("Y-m-d"), 0));
							
							while($row = $stmt->fetch(PDO::FETCH_ASSOC))
							{
								$stmt2 = $con->prepare("select * from Room where RoomID = ?");
							    $stmt2->execute(array($row['Room']));
								$row2 =  $stmt2->fetch(PDO::FETCH_ASSOC);
								
								$stmt3 = $con->prepare("select * from User where userID = ?");
							    $stmt3->execute(array($row['User']));
								$row3 =  $stmt3->fetch(PDO::FETCH_ASSOC);
								$list[$index] = "User: " . $row3['Email'] . " Room # " . $row2['RoomNum'] . " Start Date: " . $row['Start_Date'];
								$customerID[$index] = $row['CustomerID'];
								$index += 1;
								
							}
							
							if ($index == 0)
							{
								if($check == false)
								{
									if(isset($_SESSION['email']))
									{
										$_SESSION['msg'] = "There are currently no reservations at this hotel to cancel.";
										Header ("Location:process.php");
									}
					
								}
							}
							
							$lists = "";
							for($i = 0; $i <$index; $i++)
							{
								$lists = $lists."<option value=\"".$customerID[$i]."\">".$list[$i]."</option>" ;
							}
							
							echo "Pick Room to cancel <br>";
							echo "<select  name = 'rooming'>";
							echo $lists;
							echo "</select><br><br>";
							
						}
						else
						{
							$stmt = $con->prepare("select * from Customer where User = ? AND ? < Start_Date AND canceled = ?");
							$stmt->execute(array($userID, date("Y-m-d"),0));
							
							while($row = $stmt->fetch(PDO::FETCH_ASSOC))
							{
								$stmt2 = $con->prepare("select * from Room where RoomID = ?");
							    $stmt2->execute(array($row['Room']));
								$row2 =  $stmt2->fetch(PDO::FETCH_ASSOC);
								$list[$index] = $row2['RoomNum'];
								$customerID[$index] = $row['CustomerID'];
								$index += 1;
								
							}
							
							if($index == 0)
							{
								if($check == false)
								{
									if(isset($_SESSION['email']))
									{
										$_SESSION['msg'] = "You have no reservations to cancel on your account.";
										Header ("Location:process.php");
									}
							
								}

							}
							
							$lists = "";
							for($i = 0; $i <$index; $i++)
							{
								$lists = $lists."<option value=\"".$customerID[$i]."\">".$list[$i]."</option>" ;
							}
							
							echo "Pick Room to cancel <br>";
							echo "<select  name = 'rooming'>";
							echo $lists;
							echo "</select><br><br>";
							
					
						}
						
				 
				 
				?>
				
				<br> <br>
                 <span><span><input name="enter" class="btn" type="submit" value="Cancel Reservation"style="width:100px; height:25px;" /></span></span>
				 
				</form>
			</div>
		</div>
	</div>

</div>
</body>
</html>
<?php
include "footer.php";
?>
