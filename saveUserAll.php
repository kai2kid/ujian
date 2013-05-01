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
	
	if (isset($_REQUEST["edNama"]))
	{
		$allNama = explode(";",$_REQUEST["edNama"]);		
		for ($i=0; $i<sizeof($allNama); $i++)	
		{
			if (trim($allNama[$i])!="")
			{
				$baris = explode(",",$allNama[$i]);
				$query = "INSERT INTO USER (USERNAME, NAMA, PASSWORD, JENIS) VALUES ('".trim($baris[0])."','".trim($baris[1])."','".md5(trim($baris[2]))."','S')";
				mysql_query($query,$dbConn);
			}
		}
	}
	header("location:listSiswa.php");
 ?>