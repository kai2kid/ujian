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
		$query="INSERT INTO GROUP_SOAL (NAMA_GROUP) VALUES ('".$_REQUEST["nama_group"]."')";
		mysql_query($query);
	} else if (isset($_REQUEST["mode"]) && $_REQUEST["mode"]=="update") {		
		$query="UPDATE GROUP_SOAL SET NAMA_GROUP='".$_REQUEST["nama_group"]."' WHERE KODE_GROUP='".$_REQUEST["kode_group"]."'";
		mysql_query($query);
	} else if (isset($_REQUEST["mode"]) && $_REQUEST["mode"]=="delete") {		
		$query="DELETE FROM GROUP_SOAL WHERE KODE_GROUP='".$_REQUEST["kode_group"]."'";
		mysql_query($query);
	}
	
	header("location:group.php");
 ?> 
