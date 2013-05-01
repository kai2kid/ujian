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
		//insert header soal	
		$randomSoal = "0";
		$randomGroup = "0";
		if ($_REQUEST["rdRandom"]=="soal") {
			$randomSoal = "1";			
		} else {
			$randomGroup = "1";
		}
		$query="INSERT INTO UJIAN (NAMA_UJIAN, KODE_SOAL, WAKTU_MULAI, WAKTU_AKHIR, JUM_PERHALAMAN, RANDOM_SOAL, RANDOM_GROUP, RUMUS_NILAI, DURASI_PER_SOAL, DURASI_UJIAN, PENGAJAR, JENIS) values ('".$_REQUEST["edNama"]."', '".$_REQUEST["cbSoal"]."', '". $_REQUEST["waktuMulai"]."', '". $_REQUEST["waktuAkhir"]."', ".$_REQUEST["JumSoal"].", ".$randomSoal.", ".$randomGroup.", '".$_REQUEST["rumus"]."', ".$_REQUEST["DurSoal"].", ".$_REQUEST["DurUjian"].", '".$_REQUEST["Pengajar"]."', '".$_REQUEST["mode"]."')";		
		mysql_query($query,$dbConn);					
		
		$query = "SELECT KODE_UJIAN FROM UJIAN ORDER BY KODE_UJIAN DESC";
		$result = mysql_query($query);
		$baris=mysql_fetch_assoc($result);
		$kode_ujian = $baris["KODE_UJIAN"];
		
		$peserta = explode("\n",$_REQUEST["peserta"]);
		for ($i=0; $i<sizeof($peserta); $i++)
		{
			$peserta[$i] = str_replace("\n", "", trim($peserta[$i]));
			if ($peserta[$i]!="")
			{
				$query="INSERT INTO PESERTA_UJIAN (KODE_UJIAN, USERNAME) VALUES ('".$kode_ujian."','".$peserta[$i]."')";		
				mysql_query($query,$dbConn);
			}
		}				
		
		header("location:listUjian.php");
		
	} else if ($_REQUEST['modeProses']=='UBAH'){
		$randomSoal = "0";
		$randomGroup = "0";
		if ($_REQUEST["rdRandom"]=="soal") {
			$randomSoal = "1";			
		} else {
			$randomGroup = "1";
		}
		
		$query="UPDATE UJIAN SET NAMA_UJIAN='".$_REQUEST["edNama"]."', KODE_SOAL='".$_REQUEST["cbSoal"]."', WAKTU_MULAI='". $_REQUEST["waktuMulai"]."', WAKTU_AKHIR='". $_REQUEST["waktuAkhir"]."', JUM_PERHALAMAN=".$_REQUEST["JumSoal"].", RANDOM_SOAL=".$randomSoal.", RANDOM_GROUP=".$randomGroup.", RUMUS_NILAI='".$_REQUEST["rumus"]."', DURASI_PER_SOAL=".$_REQUEST["DurSoal"].", DURASI_UJIAN=".$_REQUEST["DurUjian"].", PENGAJAR='".$_REQUEST["Pengajar"]."' WHERE KODE_UJIAN='".$_REQUEST["kode_ujian"]."', JENIS='".$_REQUEST["mode"]."'";	
		mysql_query($query,$dbConn);	
		
		$query="DELETE FROM PESERTA_UJIAN WHERE KODE_UJIAN='".$_REQUEST["kode_ujian"]."'";	
		mysql_query($query,$dbConn);
		
		$peserta = explode("\n",$_REQUEST["peserta"]);
		for ($i=0; $i<sizeof($peserta); $i++)
		{
			$peserta[$i] = str_replace("\n", "", trim($peserta[$i]));
			if ($peserta[$i]!="")
			{
				$query="INSERT INTO PESERTA_UJIAN (KODE_UJIAN, USERNAME) VALUES ('".$_REQUEST["kode_ujian"]."','".trim($peserta[$i])."')";		
				mysql_query($query,$dbConn);
			}
		}	
		header("location:listUjian.php");
	} else if ($_REQUEST['modeProses']=='DELETE'){
		$query="DELETE FROM UJIAN WHERE KODE_UJIAN='".$_REQUEST["kode_ujian"]."'";
		//echo $query;
		mysql_query($query,$dbConn);

		$query="DELETE FROM PESERTA_UJIAN WHERE KODE_UJIAN='".$_REQUEST["kode_ujian"]."'";
		mysql_query($query,$dbConn);	
		header("location:listUjian.php");
	}

?>