<?php session_start(); //this must be the very first line on the php page, to register this page to use session variables
      	$_SESSION['timeout'] = time();
	
	//if this is a page that requires login always perform this session verification
	require_once "inc/sessionVerify.php";

	require_once "dbconnect.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Datatables</title>
	<style type = "text/css">
  		h1, h2 {
    		text-align: center;
  		}

		table {
    		border-top: double;
    		border-bottom: double;
    		border-right: blank
		}

		td, th { border: 1px solid }
	</style>


	<meta charset="utf-8">
	<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/favicon.ico">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">

	<title>DataTables example - Zero configuration</title>
	<link rel="stylesheet" type="text/css" href="../../media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="../../DataTables-1.10.3/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="../../DataTables-1.10.3/examples/resources/demo.css">
	<style type="text/css" class="init">

	</style>
	<script type="text/javascript" language="javascript" src="../../media/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../../media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="../../DataTables-1.10.3/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="../../DataTables-1.10.3/examples/resources/demo.js"></script>
	<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
    $('#example').DataTable();
} );
	</script>

	</head>
<body>

<?php
$stmt = $con->prepare("select * from User");
$stmt->execute();

print '<br /><br /><span style="color:red">Data retrieved from database:</span><br/ >';
print '<table id="example" class="display" cellspacing="0" width="100%">';
print '<thead>
<tr><th>UserID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Password</th><th>Phone Number</th></tr></thead><tfoot>
<tr><th>UserID</th><th>First Name</th><th>Last Name</th><th>Username</th><th>Password</th><th>Phone Number</th></tr></tfoot>';

			
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
{
	print '<tr>';
	if($row['Email'] != -1)
	{
	
		print '<td>'.$row["UserID"]."</td><td>".$row["FirstName"]."</td><td>".$row["LastName"]."</td><td>".$row["Email"]."</td><td>".$row["Password"]."</td><td>".$row["Phone"]."</td>";

		print "</tr>";	
	}
	
	
}

	$stmt->closeCursor();
			
	print '</table>';

?>

<a href = "mainAccount.php"> Back to Admin Page</a>
	</body>
</html>