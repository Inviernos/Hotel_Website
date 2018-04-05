<?php
session_start();
require_once "header.php";
  require_once "inc/util.php";
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
<html>
<head>
<title>Restaurant - Hotel Website</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="description" content="Check out the restaurant page of the Free Hotel Website Template from Template Monster."/>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
<script src="maxheight.js" type="text/javascript"></script>

</head>
<body id="page4" onload="new ElementMaxHeight();">
<div id="main">
	<!-- header -->
	<div id="header" class="small">
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
		
		<div class="row-2 alt">
	 		<div class="indent">
<!-- header-box-small begin -->
				<div class="header-box-small">
					<div class="inner">
						<ul class="nav">
					 		<li><a href="index.php">Home page</a></li>
							<li><a href="mainAccount.php">Account</a></li>
							<li><a href="gallery.php">Gallery</a></li>
							<li><a href="restaurant.php" class="current">Restaurant</a></li>
							<li><a href="booking.php">Booking</a></li>
						</ul>
					</div>
				</div>
<!-- header-box-small end -->
			</div>
		</div>
	</div><div class="ic">Lovely Flash templates from TemplateMonster!</div>
<!-- content -->
	<div id="content">
 	
		<div class="container">
			<div class="aside maxheight">
<!-- box begin -->
				<div class="box maxheight">
					<div class="inner">
						<h3>Menu</h3>
						<ul class="list3">
					 		<li><a href="#">Specials</a></li>
							<li><a href="#">Lunch</a></li>
							<li><a href="#">Diner</a></li>
							<li><a href="#">Beverage</a></li>
							<li><a href="#">Winery</a></li>
							<li><a href="#">Dessert</a></li>
							<li><a href="#">Italian</a></li>
							<li><a href="#">French</a></li>
							<li><a href="#">German</a></li>
						</ul>
						
					</div>
				</div>
<!-- box end -->
			</div>
			<div class="content">
				<div class="indent">
					<h2>Today’s featured menu item</h2>
					<img class="img-indent png alt" alt="" src="images/4page-img1.png" />
					<div class="extra-wrap">
						<h5>Foie gras!</h5>
						<ul class="list2">
							<li>Nice and tasty!</li>
							<li>Made from French ingredients!</li>
							<li>Cooked by Italian chef!</li>
							<li>Awarded by Czech assosiation of chef!</li>
							<li>Proved to be good for your health!</li>
						</ul>
						<div class="aligncenter"><strong class="txt2">AS LOW AS €19!</strong></div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>

</div>
</body>
</html>
<?php  
include "footer.php";
?>