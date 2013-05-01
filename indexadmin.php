<?php
 session_start();
  if ($_SESSION['JENIS']!='A'){
    header("location:index.php");
  }
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("_head.php"); ?>
<body>
<center>
<div id="header"></div>
	<div id="main" style="text-align: center;">
		<div id="welcome" class="post" style=''>
			<div><h3 class="title">
        Selamat Datang, <?php echo $_SESSION['NAMA']; ?>
			</h3></div>
      <br>
				<table cellspacing="4" cellpadding="2" style='margin:auto'>	
					<tr>
						<td width="200px" height="30px" align=left>
							<a href="group.php">Daftar Group Soal</a>
						</td>						
					</tr>
					<tr>
						<td width="200px" height="30px" align=left>
							<a href="mapel.php">Daftar Mata Pelajaran</a>
						</td>						
					</tr>
					<tr>
						<td width="200px" height="30px" align=left>
							<a href="listUser.php">Daftar Guru</a>
						</td>						
					</tr>					
					<tr>
						<td width="200px" height="30px" align=left>
							<a href="listSiswa.php">Daftar Siswa</a>
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
