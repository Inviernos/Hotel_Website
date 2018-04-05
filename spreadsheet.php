<?php session_start();
/**
 * This file defines PDO database package. This file is included in any files that needs database connection
 * http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers
 * http://php.net/manual/en/pdostatement.fetch.php
  */
require_once "dbconnect.php";
require_once "inc/util.php";
require_once "inc/sessionVerify.php";
$_SESSION['timeout'] = time();
if(isset($_SESSION['email']))
{
	$stmt = $con->prepare("select * from User where Email = ?");
    $stmt->execute(array($_SESSION['email']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);    
	if($row['Type'] == 2)
	{
		  echo "<script>window.close();</script>";
	}

}

//variables
$userInfo = array();
$userTable = array("UserID", "First Name", "Last Name", "Email" , "Password" , "Phone" , "Code" , "Activated" , "Type");
$customerInfo = array();
$customerTable = array("CustomerID" , "User" , "Room" , "canceled" , "Start_Date" , "End_Date" , "Total");
$roomInfo = array();
$roomTable = array("RoomID" , "# of rooms" , "Room Number");
$typeInfo = array();
$typeTable = array("TypeID" , "User Type");

$space = array(" ");

$stmt = $con->prepare("select * from User");
$stmt->execute();
  
//go through user table
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	array_push($userInfo, array($row['UserID'] , $row['FirstName'] , $row['LastName'] , $row['Email'] , $row['Password'] , $row['Phone'] , $row['Code'] , $row['Activated'] , $row['Type'])); 
}

$stmt = $con->prepare("select * from Customer");
$stmt->execute();
  
//go through customer table
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	array_push($customerInfo, array($row['CustomerID'] , $row['User'] , $row['Room'] , $row['canceled'] , $row['Start_Date'] , $row['End_Date'] , $row['Total'])); 
}

$stmt = $con->prepare("select * from Type");
$stmt->execute();

//go through Type table
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	array_push($typeInfo, array($row['TypeID'] , $row['UserType'])); 
}

$stmt = $con->prepare("select * from Room");
$stmt->execute();

//go through Type table
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	array_push($roomInfo, array($row['RoomID'] , $row['rooms'] , $row['RoomNum'])); 
}

//create the csv file if it doesn't exist
$fp = fopen("csv/data.csv", "w");  

//write the header for user table
fputcsv($fp, $userTable);

//write the user info into the csv file
foreach($userInfo as $fields)
{
	fputcsv($fp, $fields);
}


//Add space between the data
fputcsv($fp, $space);
fputcsv($fp, $space);
fputcsv($fp, $space);

//write the header for customer table
fputcsv($fp, $customerTable);

//write the customer info into the csv file
foreach($customerInfo as $fields)
{
	fputcsv($fp, $fields);
}

//add space between the data
fputcsv($fp, $space);
fputcsv($fp, $space);
fputcsv($fp, $space);

//write the header for the type table
fputcsv($fp, $roomTable);

//write the type info into the csv file
foreach($roomInfo as $fields)
{
	fputcsv($fp, $fields);
}

//add space between the data
fputcsv($fp, $space);
fputcsv($fp, $space);
fputcsv($fp, $space);

//write the header for the type table
fputcsv($fp, $typeTable);

//write the type info into the csv file
foreach($typeInfo as $fields)
{
	fputcsv($fp, $fields);
}

//close the file handler
fclose($fp);

Header ("Location:csv/data.csv?");	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Download Spreadsheet</title>
	
</head>
<body>


</body>
</html>
