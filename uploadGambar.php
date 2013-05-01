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
	if($_FILES["zip_file"]["name"]) {
	$filename = $_FILES["zip_file"]["name"];
	$source = $_FILES["zip_file"]["tmp_name"];
	$type = $_FILES["zip_file"]["type"];
	$name = explode(".", $filename);
	$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
	foreach($accepted_types as $mime_type) {
		if($mime_type == $type) {
			$okay = true;
			break;
		} 
	}
	$thisdir = getcwd(); 
	$continue = strtolower($name[1]) == 'zip' ? true : false;
	if(!$continue) {
		$message = "The file you are trying to upload is not a .zip file. Please try again.";
	}
 
	$target_path = $thisdir."/imgSoal/".$_REQUEST["cbSoal"].$filename;  // change this to the correct site path
	if(move_uploaded_file($source, $target_path)) {
		$zip = new ZipArchive();
		$x = $zip->open($target_path);
		if ($x === true) {
			$zip->extractTo($thisdir."/imgSoal/".$_REQUEST["cbSoal"]); // change this to the correct site path
			$zip->close();
 
			unlink($target_path);
		}
		$message = "<font style='color:blue;'>File Anda telah terupload </font>";
	} else {	
		$message = "<font style='color:red;'>Terdapat masalah dalam proses upload. Silakan ulangi lagi proses upload.</font>";
	}
}
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("_head.php"); ?>
<body>
<center>
<div id="header"></div>
	<div id="main" class="post">		
		<h2 class="title" ><span> Daftar Soal </span></h2>
		<?php if($message) echo "<p>$message</p>"; ?><br>
		<form enctype="multipart/form-data" method="post" action="" style='padding: 0 10px 0 10px;'>
		<b>Nama Soal : </b><select name="cbSoal" id="cbSoal">
		<?php
			$query="SELECT KODE_SOAL, NAMA_SOAL FROM HSOAL ORDER BY NAMA_SOAL ASC";
			$result = mysql_query($query);
			while($baris=mysql_fetch_assoc($result)){				
				echo "<option value='".$baris["KODE_SOAL"]."'>".$baris["NAMA_SOAL"]."</option>";
			}		
		?>
		</select><br>
		<label><b>Pilih file zip yang berisi kumpulan gambar : </b><input type="file" name="zip_file" /></label>
		<br />
		<input type="submit" name="submit" value="Upload" /><br><br><br>
		<center><a href="indexguru.php">Kembali ke halaman index</a></center><br>
	</div>
<?php include("_footer.php"); ?>
</center>
</body>
</html>