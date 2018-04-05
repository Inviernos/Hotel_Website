<?php 
session_start();
/*
Name: Anthony Golubski-Allen
Date: 10/19/2017
Filename: getName.php
Purpose: find the data and show it in the drop down list

*/

	require_once "dbconnect2.php";




// Fill up array with names

$sql = "select distinct Email from User"; 
$a =$DB->Execute($sql);



// get the q parameter from URL
$q = $_REQUEST["q"]; 

$hint="";

// lookup all hints from array if $q is different from "" 
if ($q !== "")
{ 	
	$q=strtolower($q); 
	$len=strlen($q);

	
    	foreach($a as $name)
    	{ 

		if (stristr($q, substr($name['Email'],0,$len))) //test if $q matches with the first few characters of the same length in the lastname
      		{ 
			if ($hint==="")
       		{ 
				$hint = "<option>". $name['Email']."</option>";
 			}
        		else
        		{ 	
					$hint .= "<option>". $name['Email']."</option>";
				}
      		}
    	}
}


print $hint;
?>