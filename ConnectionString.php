<?php
 $dbConn = mysql_connect("localhost","root","");
// $dbConn = mysql_connect("192.168.9.10","ujianfratz","passfratz");
 $dbSelect = mysql_select_db("db_ujianfratz",$dbConn);  

 ini_set('session.cookie_lifetime', 0);
?>