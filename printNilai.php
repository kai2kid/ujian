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
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<body onload="window.print()">
	<?php
		echo "<table width='560px' align='center'><tr><td><b><u>NILAI ".strtoupper($_REQUEST["nama_ujian"])."</u></b></td></tr></table>";
		echo "<br>";
		echo $_REQUEST["hasil_nilai"];
	
	?>
</body>
</html>