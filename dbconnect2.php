<?php
/**
 * This file defines database connection. This file is included in any files that needs database connection
 * 
  */



include ("adodb5/adodb.inc.php");
include ("adodb5/adodb-exceptions.inc.php");

$DB = NewADOConnection("mysql");

$DB->Connect("localhost", "adgolubs", "adgolubs","adgolubs_db");

?>
