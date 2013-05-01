<?php include_once("ConnectionString.php"); ?>
<?php include_once("class_ujian.php"); ?>
<?php
  session_start();
  if ($_SESSION['JENIS']!='S'){
    header("location:index.php");
  }
  $user = $_SESSION['USER'];
  $ujian = new ujian($user,0,$dbConn);  
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("_head.php"); ?>
  <body>
  <center>
    <div id="header"></div>
		<div id="main" class="post">
      <div>
			  <h2 class="title">
			    Latihan Ujian untuk <?php echo $_SESSION['NAMA']; ?>
        </h2>
      </div>
      <?php echo $ujian->drawAvailableUjian(1); ?>
			<div class="meta" style='text-align:center'>
				<a href="indexsiswa.php">Kembali ke halaman index</a>
			</div>
		</div>
<?php include("_footer.php"); ?>
</center>
  </body>
</html>