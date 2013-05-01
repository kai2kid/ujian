<?php include("ConnectionString.php"); ?>
<?php
  session_start();  
    if (isset($_SESSION['JENIS'])){
		if ($_SESSION['JENIS']!='A' && $_SESSION['JENIS']!='G'){
			header("location:index.php");
		}
	} else {
		header("location:index.php");
	}
	
	if (isset($_REQUEST["mode"]) && $_REQUEST["mode"]=="insert")
	{	
		$query="INSERT INTO MAPEL (NAMA_MAPEL) VALUES ('".$_REQUEST["nama_mapel"]."')";
		mysql_query($query);
	} else if (isset($_REQUEST["mode"]) && $_REQUEST["mode"]=="update") {		
		$query="UPDATE MAPEL SET NAMA_MAPEL='".$_REQUEST["nama_mapel"]."' WHERE KODE_MAPEL='".$_REQUEST["kode_mapel"]."'";
		mysql_query($query);
	} else if (isset($_REQUEST["mode"]) && $_REQUEST["mode"]=="delete") {		
		$query="DELETE FROM MAPEL WHERE KODE_MAPEL='".$_REQUEST["kode_mapel"]."'";
		mysql_query($query);
	}
	
	header("location:mapel.php");
 ?> 
