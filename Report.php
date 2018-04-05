<?php
 /*
Name: Anthony Golubski-Allen
Date: 9/8/2017
Filename: Report.php
Purpose: Shows monthly report with a graph
*/

require_once "dbconnect.php";
require_once "inc/util.php";
require_once "header.php";

$adminlink = "";


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

	
	
	$check = false;
	$msg = "";
	$days = date("t");
	$month = date('m', strtotime(date('Y-m')." -1 month"));
	
	if($month == 12)
	{
		$year = date('Y', strtotime(date('Y-m')." -1 year"));
	}
	else
	{
		$year = date("Y");
	}

	$start = $year . "-" . $month . "-1";
	$end =  $year . "-" . $month . "-" . $days;

	$stmt = $con->prepare("select * from Customer where canceled = ? and Start_Date >= ? and Start_Date <= ?");
	$stmt->execute(array(0,$start,$end));
	$day = array_fill(0, $days+1, 0);
	$maxValue = 0;

	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		
        $date = date("j" , strtotime($row['Start_Date'])  );
		$index = (int)$date;
		
		
		$day[$index] += (int)$row['Total'];

	}

	$maxValue = (int)max($day);
	$month = date('F', strtotime(date('Y-m')." -1 month"));
?>





<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Services - Free Hotel Website Template designed by TemplateMonster</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <link rel="stylesheet" href="demos.css" type="text/css" media="screen" />

<script src="../RGraph/libraries/RGraph.common.core.js" ></script>
<script src="../RGraph/libraries/RGraph.scatter.js" ></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>


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

 <script>
   $(document).ready(function ()
        {
            /**
            * Generate the financial data
            */
           
            var data = [];
			var weekLabel = [];
			var index = 0;
			var max = <?php echo json_encode($maxValue); ?>;
			var month = <?php echo json_encode($month); ?>;
			var monthReport = <?php echo json_encode($day); ?>;
			
		
			
            for (var i=0; i<monthReport.length+1; i+=1) {
                value = monthReport[i];
				data.push([i,value]);
			
				
				if(i % 7 == 0)
				{
					weekLabel[index] = "week " + (index+1);
					index+=1;
				}
				
            }
            
		
			
			
            var scatter = new RGraph.Scatter('cvs', data)
                .set('ymax', max)
                .set('xmin', 0)
                .set('xmax', data.length)
                .set('labels', weekLabel)
                .set('tickmarks', null)
                .set('line', true)
				.set('chart.text.size', 11)
				.set('chart.text.color', "red")
                .set('line.colors', ['green'])
				.set('title.color' , "red")
                .set('units.pre', '$')
                .set('gutter.left', 75)
                .set('title', month)
                .set('background.grid.autofit.numvlines', 64)
                .set('background.grid.autofit.numhlines', 20)
                .set('background.grid.color', '#eee')
				.set('chart.line.linewidth', 5)
                .draw();
        })
</script>
 
</head>
<body id="page1" onload="new ElementMaxHeight();">

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
					<?php
					  print $msg;
					?>
					
					<h2>Monthly Report</h2>
					
			

					<canvas id="cvs" width="900" height="500">[No canvas support]</canvas>
			
			</div>
		</div>
	</div>

</div>
</body>
</html>






			
			

<?php  
include "footer.php";
?>