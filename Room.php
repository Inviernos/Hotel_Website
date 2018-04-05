<?php
/**
 * This file defines PDO database package. This file is included in any files that needs database connection
 * http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers
 * http://php.net/manual/en/pdostatement.fetch.php
  */
require_once "dbconnect.php";
require_once "inc/util.php";



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Process Query Strings</title>
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

	</head>

<body>

<?php
$stmt = $con->prepare("select * from Room");
$stmt->execute();

print '<br /><br /><span style="color:red">Data retrieved from database:</span><br/ >';
print '<table>';
print '<tr><td>RoomID</td><td>rooms</td><td>Room#</td></tr>';
			
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
{
	print '<tr>';
	print '<td>'.$row["RoomID"]."</td><td>".$row["rooms"]."</td><td>".$row["RoomNum"]."</td>";

    print "</tr>";	
	
}

	$stmt->closeCursor();
			
	print '</table>';

?>


	</body>
</html>
