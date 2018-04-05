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
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Photos Gallery - Free Hotel Website Template designed by TemplateMonster</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="description" content="This stunning web page is Gallery of a Free Hotel Website Template from Template Monster."/>
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
<body id="page3" onload="new ElementMaxHeight();">
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
							<li><a href="gallery.php" class="current">Gallery</a></li>
							<li><a href="restaurant.php">Restaurant</a></li>
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
						<h3>Browse Images</h3>
						<div class="gallery-images">
							<ul>
								<li><a href="#"><img alt="" src="images/3page-img1.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img2.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img3.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img4.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img5.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img6.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img7.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img8.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img9.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img10.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img11.jpg" /></a></li>
								<li><a href="#"><img alt="" src="images/3page-img12.jpg" /></a></li>
							</ul>
						</div>
					</div>
				</div>
<!-- box end -->
			</div>
			<div class="content">
				<div class="indent">
					<h2>Hotel’s picture gallery</h2>
					<div class="gallery-main png">
						<div class="inner">
				 			<img alt="" src="images/3page-img13.jpg" />
							<div class="prev"><a href="#"><img alt="" src="images/prev.png" class="png" /></a></div>
							<div class="next"><a href="#"><img alt="" src="images/next.png" class="png" /></a></div>
						</div>
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