<?php
session_start();
require_once "header.php";
require_once "inc/util.php";
require_once "dbconnect.php";
require_once "inc/sessionVerify.php";
 $_SESSION['timeout'] = time();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Reservation and Booking - Free Hotel Website Template designed by TemplateMonster</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="description" content="Feel free to visit a Booking page of a Free Hotel Website Template from TemplateMonster.com."/>
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
<body id="page5" onload="new ElementMaxHeight();">

<?php

	
	$startDay = "";
	$startMonth = "";
	$startYear = "";
	$startDate = "";
	
	$endDay = "";
	$endMonth = "";
	$endYear = "";
	$endDate = "";
	
	$message = "";
	
	$rooms = "";
	$admin = "";
	
	//user has pressed button for booking
	if (isset($_POST['enter']))
	{
		
		$startDay = trim($_POST['day']);
		$startMonth = trim($_POST['month']);
		$startYear = trim($_POST['year']);
		
		$startDate = $startYear . "-" . $startMonth . "-" . $startDay;
		
		$endDay = trim($_POST['eday']);
		$endMonth = trim($_POST['emonth']);
		$endYear = trim($_POST['eyear']);
		
		$endDate = $endYear . "-" . $endMonth . "-" . $endDay;
		
		if(isset($_SESSION['email']))
		{
			if(strtotime($startDate) > strtotime($endDate))
			{
				if(strtotime($startDate) < strtotime(date("Y-m-d")))
				{
					$_SESSION['msg'] = "You have selected a start date after the end date. Also, you have selected a date in the past.";
				}	
				else
				{
					$_SESSION['msg'] = "You have selected a start date after the end date";
					
				}
				
				Header ("Location:process.php");
			}
			else if(strtotime($startDate) < strtotime(date("Y-m-d")))
			{
				$_SESSION['msg'] = "You have selected a date from the past";
				Header ("Location:process.php");
			}
			else
			{
				$rooms = trim($_POST['room']);
			
				$_SESSION['start'] = $startDate;
				$_SESSION['end']  = $endDate;
				$_SESSION['room'] = (int)$rooms;
			
				Header ("Location:addReservation.php?");
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
		$admin = '<a href = "deleteUser.php">Delete Users Account</a> <br />';
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
					 		<li><a href="index.php">Home page</a></li>
							<li><a href="mainAccount.php">Account</a></li>
							<li><a href="gallery.php">Gallery</a></li>
							<li><a href="restaurant.php">Restaurant</a></li>
							<li><a href="booking.php" class="current">Booking</a></li>
						</ul>
					</div>
				</div>
<!-- header-box end -->
			</div>
		</div>
	</div>
<!-- content -->
	<div id="content">
		<div class="wrapper">
			<div class="aside maxheight">
<!-- box begin -->
				<div class="box maxheight">
					<div class="inner">
						<h3>Reservation:</h3>
						<form action="booking.php" method="post" id="reservation-form">
				 			<fieldset>
							
							<div class="field"><label>Check In:</label>
							<?php
							
							 echo "<select  name = 'month'>";
							 echo  monthList(1);
							 echo "</select>";
							
                             echo "&nbsp;";
							 
     						 echo "<select  name = 'day'>";
							 echo dayList(1);
							 echo "</select>";
							 
					         echo "&nbsp;";
							 
							 echo "<select name = 'year'";
							 echo futureYearList(20);
							 echo "</select>";
			
			
					
							?>
							</div>
							<div class="field"><label>Check Out:</label>
							<?php
									 echo "<select  name = 'emonth'>";
									 echo  monthList(1);
									 echo "</select>";
									
									 echo "&nbsp;";

									 echo "<select  name = 'eday'>";
									 echo dayList(1);
									 echo "</select>";
									 
							         echo "&nbsp;";
									 
									 echo "<select name = 'eyear'";
									 echo futureYearList(20);
									 echo "</select>";
							?>

							</div>
						
								&nbsp;Rooms:
							<select  name = "room">
								<option value = "1" selected>1</option>
								<option value = "2">2</option>
								<option value = "3">3</option>
							</select>
							
							<br><br>
							<span><span><input name="enter" class="btn" type="submit" value="Check Availability"style="width:100px; height:25px;" /></span></span>
							
							<br><br>
							<a href = "CancelReservation.php" >Cancel Reservation</a>
							</fieldset>
						</form>
					</div>
				</div>
<!-- box end -->
			</div>
			<div class="content">
				<div class="indent">
					<h2>Our location</h2>
					<img class="img-indent png" alt="" src="images/5page-img1.png" />
					<div class="extra-wrap">
						<p class="alt-top">Our hotel is located in the most spectacular part of Prague - surrounded by boutiques, restaurants and luxurious shops.</p>
						<p>Please feel free to come visit us at the following adress:</p>
						<dl class="contacts-list">
							<dt>Gazek st., 210</dt>
							<dd>1-800-412-4556</dd>
							<dd>1-800-542-6448</dd>
						</dl>
					</div>
					<div class="clear"></div>
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