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
<?php include("_head.php"); ?>
<body>
<center>
<div id="header"></div>
	<?php
		unset($_SESSION["kode_soal"]);
	?>
		<div id="main" class="post">		
			<h2 class="title" ><span>
			Tambah Siswa dalam Jumlah Banyak
			</span></h2>
			<center>		
			<form action="saveUserAll.php" method="post">			
			<table style="font: normal small 'Trebuchet MS', Arial, Helvetica, sans-serif;">
				<tr>
					<td valign="top"  align="right">
						<b>Daftar User Baru</b>
					</td>
					<td valign="top">
						:
					</td>
					<td>
						<textarea name="edNama" id="edNama" cols="60" rows="15" style="resize:none;"></textarea>
					</td>
				</tr>
				<tr>
					<td valign="top">
						&nbsp;
					</td>
					<td valign="top">
						&nbsp;
					</td>
					<td align="right">
						<input type="submit" value="Proses">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
						<b>Format </b>
					</td>
					<td valign="top">
						:
					</td>
					<td align="left">						
						username, nama, password;<br>
						username, nama, password;<br>
						username, nama, password;<br>
						username, nama, password;<br>
						username, nama, password;<br>
						username, nama, password;<br>						
					</td>
				</tr>
			</table>
				<div class="meta">
					<br>					
					<center>
					<?php					  
						echo'<a href="listSiswa.php">Kembali ke halaman daftar siswa</a></center>';					  
					 ?>
					 </center>
				</div>
		</div>
<?php include("_footer.php"); ?>
</center>
</body>
</html>