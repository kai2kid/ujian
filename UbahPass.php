<?php
 session_start();
  if (!isset($_SESSION['JENIS'])){
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
			<div>
			<h3 class="title">
				Selamat Datang, <? echo $_SESSION['NAMA'];?>      
			</h3>
			</div>
			<br>
			<form action="SavePass.php" method="post">
			<table style="margin-left: 30px;" >
				<tr>
					<td width="210px">Password Lama</td>
					<td width="5px"> : </td>
					<td><input type="password" name="edOldPass" id="edOldPass" size="35"></td>
				</tr>
				<tr>
					<td>Password Baru</td>
					<td> : </td>
					<td><input type="password" name="edNewPass" id="edNewPass" size="35"></td>
				</tr>
				<tr>
					<td>Konfirmasi Password Baru</td>
					<td> : </td>
					<td><input type="password" name="edNewCPass" id="edNewCPass" size="35"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td align="left"><input type="submit" value="Ubah Password">
					<?php
						if (isset($_REQUEST["msg"]))
						{
							echo "<label ";
							if ($_REQUEST["msg"]=="1"){
								echo "style='color: blue;'>Perubahan password telah berhasil!";
							} else if ($_REQUEST["msg"]=="2"){
								echo "style='color: red;'>Password lama tidak sesuai!";
							} else if ($_REQUEST["msg"]=="3"){
								echo "style='color: red;'>Password baru dan konfirmasinya harus sama!";
							} else if ($_REQUEST["msg"]=="4"){
								echo ">";
							}
							echo "</label>";
						}
					?>
					</td>
				</tr>
			</table>
			</form>
			<div class="meta">
				<br/>
				<center>
				<?php
				  if ($_SESSION['JENIS']=='A'){
					echo'<a href="indexadmin.php">Kembali ke halaman index</a></center>';
				  }
				  if ($_SESSION['JENIS']=='G'){
					echo'<a href="indexguru.php">Kembali ke halaman index</a></center>';
				  }
				  if ($_SESSION['JENIS']=='S'){
					echo'<a href="indexsiswa.php">Kembali ke halaman index</a></center>';
				  }
				 ?>
			</div>
		</div>
	</div>
<?php include("_footer.php"); ?>
</center>
</body>
</html>
