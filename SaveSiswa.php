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
   
	if ($_REQUEST['modeProses']=='INSERT'){
		$query = "SELECT USERNAME FROM USER WHERE USERNAME='".$_REQUEST["edUser"]."'";
		$result = mysql_query($query);
		$baris=mysql_fetch_assoc($result);
		
		if ($baris)
		{
			header("location:tambahUser.php?user='".$baris["USERNAME"]."'");
		} else {
			$query="INSERT INTO USER (USERNAME, NAMA, PASSWORD, JENIS) VALUES ('".$_REQUEST["edUser"]."', '".$_REQUEST["edNama"]."', '".md5($_REQUEST["edPass"])."', '".$_REQUEST["rdJenis"]."')";		
			mysql_query($query,$dbConn);	
			header("location:listSiswa.php");
		}		
	} else if ($_REQUEST['modeProses']=='UBAH'){
		if ($_REQUEST["edPass"]=="") {
			$query="UPDATE USER SET NAMA='".$_REQUEST["edNama"]."', JENIS='".$_REQUEST["rdJenis"]."' WHERE USERNAME='".$_REQUEST["edUser"]."'";					
		} else {		
			$query="UPDATE USER SET NAMA='".$_REQUEST["edNama"]."', PASSWORD='".md5($_REQUEST["edPass"])."', JENIS='".$_REQUEST["rdJenis"]."' WHERE USERNAME='".$_REQUEST["edUser"]."'";		
		}
		mysql_query($query,$dbConn);			
		header("location:listSiswa.php");
	} else if ($_REQUEST['modeProses']=='DELETE'){
		$query="DELETE FROM USER WHERE USERNAME='".$_REQUEST["username"]."'";		
		mysql_query($query,$dbConn);
		
		header("location:listSiswa.php");
	}
?>
