<?php
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

<script>
//Ajax tutorial - http://www.w3schools.com/ajax/
function showHint(str)
{
	var xmlhttp;
	if (str.length==0)
	{ 
	  //document.getElementById("txtHint").innerHTML="";
	  return;
	}
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
	xmlhttp.onreadystatechange=function()
	{
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	  {
		document.getElementById("browsers").innerHTML=xmlhttp.responseText;
	  }
	}
	
	xmlhttp.open("GET","getEmail.php?q="+str,true);
	xmlhttp.send();
}

</script>

</head>
<body id="page1" onload="new ElementMaxHeight();">

<?php

   $admin = "";
   $stmt = $con->prepare("select * from User where Email = ?");
   $stmt->execute(array($_SESSION['email']));
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   $user = "";
   $roomNum = "";
   $canceled = 0;
   $total = rand(1000, 2000);
   $adminlink = "";  
   $datalist = "";
   
   //user has submit enter
   if (isset($_POST['enter']))
   {
		//get room number
		$roomNum = trim($_POST['Rooms']);
		
		$stmt2 = $con->prepare("select * from Room where roomNum = ?");
		$stmt2->execute(array($roomNum));
		$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
		
		//get user if your admin
		if(isset($_POST['user']))
		{
			$user = trim($_POST['user']);
			
			$stmt3 = $con->prepare("select * from User where Email = ?");
			$stmt3->execute(array($user));
			$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
			
			if($row3 == 0)
			{
				echo "Username is not in the database";
			}
			else
			{
				//use stored procedures
				$sql = "Call SP_INSERT_CUSTOMER('".$row3['UserID']."', '".$row2['RoomID']."', '".$canceled."', '".$_SESSION['start']."','".$_SESSION['end']."', '".$total."');"; 		
				$stmt = $con->query($sql);
			
				$_SESSION['msg'] = "Added " . $roomNum . " To your reservation";
				Header ("Location:process.php");
			}
	
		}
		else
		{
			//use stored procedures
			$sql = "Call SP_INSERT_CUSTOMER('".$row['UserID']."', '".$row2['RoomID']."', '".$canceled."', '".$_SESSION['start']."','".$_SESSION['end']."', '".$total."');"; 		
			$stmt = $con->query($sql);
			
			$_SESSION['msg'] = "Added " . $roomNum . " To your reservation";
			Header ("Location:process.php");
		}
		
		
		

   }
   
   if($row['Type'] == 2)
   {
	   $admin = '&nbsp;Put User Name: <input type="email" list="browsers" maxlength = "50" value="" name="user" id="user" placeholder="Email" onkeyup="showHint(this.value)"/> <br><br> ';
	   $datalist = '<datalist id="browsers" > </datalist>';
	   $adminlink = '<a href = "deleteUser.php">Delete Users Account</a> <br />';
   }
   
	$stmt = $con->prepare("select * from Room where rooms = ?");
	$stmt->execute(array($_SESSION['room']));
						
	$list = array();	
	$isAvailable = false;
	$isDuplicate = false;
	$check = false;
	$index = 0;
						
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$stmt2 = $con->prepare("select * from Customer where Room = ? AND canceled = ?");
		$stmt2->execute(array($row['RoomID'], 0));
	
		while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC))
		{
			
			$check = true;
			if($_SESSION['start'] < $row2['Start_Date'])
			{
				if($_SESSION['end'] < $row2['Start_Date'])
				{
					$isAvailable = true;
				}
			}
			else if($_SESSION['start'] > $row2['End_Date'])
			{
					$isAvailable = true;
			}
			else
			{
					$isAvailable = false;
					break;
			}					
									
		}
		
		if($check == false)
		{
			$list[$index] = $row['RoomNum'];
			$index += 1;
		}
		else
		{
			if($isAvailable)
			{
			
				$list[$index] = $row['RoomNum'];
				$index += 1;
				$isAvailable = false;
			}
			
			$check = false;
		}
					
						
	}
	
	if($index == 0)
	{
		if(isset($_SESSION['email']))
		{
			$_SESSION['msg'] = "There are no rooms with your specifications to reserve";
			Header ("Location:process.php");
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
			
			 
				<form action="addReservation.php" method="post">
				 
				 <?php
				 
						
						$lists = "";
						for($i = 0; $i <$index; $i++)
						{
							$lists = $lists."<option value=\"".$list[$i]."\">".$list[$i]."</option>" ;
						}
						
						echo "Pick Room <br>";
						echo "<select  name = 'Rooms'>";
						echo $lists;
						echo "</select><br><br>";
						
						echo $admin;
						echo $datalist;
				 
				 
				?>
				
				<br> <br>
                 <span><span><input name="enter" class="btn" type="submit" value="Check Out Room"style="width:100px; height:25px;" /></span></span>
		
				 
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
