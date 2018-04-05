<?php

$links = "";

if (!isset($_SESSION['email']))
{
	$links = '<a href = "Login.php">Sign In</a> <br />';
}
else
{
	$links = '<a href = "logout.php">Log Out</a> <br />';
}

?>