<?php
 session_start();
 //$_SESSION['timeout'] = 0;
  if ($_SESSION['JENIS']!='S'){
     header("location:index.php");
  }
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("_head.php"); ?>
<body>
<center>
<div id="header"></div>
	<div id="main" style="text-align: center;">
		<div id="welcome" class="post">
      <div><h2 class="title">
        Selamat Datang, <?php echo $_SESSION['NAMA']; ?>
      </h2></div>
      <br>
		<table cellspacing=2 cellpadding="5" style='margin: auto;'>
			<tr>
				<td align=left>
					<a href="UjianSiswa.php">Ujian</a>
				</td>
          </tr>
          <tr>
            <td align=left>
              <a href="LatihanSiswa.php">Latihan Ujian</a>
            </td>
          </tr>
          <tr>
            <td align=left>
				<a href="Lihat_NilaiSiswa.php">Lihat Nilai</a>
			</td>
          </tr>
		  <tr>
            <td align=left>
				<a href="UbahPass.php">Ubah Password</a>
			</td>
          </tr>
          <tr>
            <td align=left>
				<a href="index.php?logout=true">Log Out</a>
			</td>
		</tr>
        </table>
		</div>
	</div>
<?php include("_footer.php"); ?>
</center>
</body>
</html>
