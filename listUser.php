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
			Daftar Guru
			</span></h2>
			<center>			
			<table border="1" cellpadding="5" cellspacing="0">
				<tr>					
					<td class="judulTable" width="175px">
						Username
					</td>
					<td class="judulTable" width="350px">
						Nama
					</td>								
					<td class="judulTable" width="130px" colspan="2">					
						Action
					</td>
				</tr>
				<?php
					$query="SELECT USERNAME, NAMA FROM USER WHERE JENIS='G' ORDER BY JENIS, USERNAME";
					$result=mysql_query($query);
					while($row=mysql_fetch_assoc($result)){

					  echo("<tr>");				
					  echo('<td class="log2" valign="top">'. $row['USERNAME'] ."</td>");
					  echo('<td class="log2" valign="top">'. strtoupper($row['NAMA']) ."</td>");					  
					  echo('<td class="log2" valign="middle"><center><form method="post" action="EditUser.php"><input type="hidden" name="username" value="'.$row["USERNAME"].'"><input type="submit" value="Ubah"></center></form></td>');
					  echo('<td style="border-left:0px;" valign="middle"><center><form method="post" action="SaveUser.php"><input type="hidden" name="modeProses" id="modeProses" value="DELETE"><input type="hidden" name="username" id="username" value="'.$row["USERNAME"].'"><input type="submit" value="Hapus" onclick="return confirm(\'Apakah Anda yakin?\');"></form></center></td>');
					  echo("</tr>");
					}
				?>
			</table>
			</center>
			<div class="meta">
				<br>
				<center>
				<input type="button" value="Tambah Guru" onClick="document.location.href='TambahGuru.php';"> 				
				</center>
				<br/>
				<center>
				<?php
				  if ($_SESSION['JENIS']=='A'){
					echo'<a href="indexadmin.php">Kembali ke halaman index</a></center>';
				  }
				  if ($_SESSION['JENIS']=='G'){
					echo'<a href="indexguru.php">Kembali ke halaman index</a></center>';
				  }
				 ?>
			</div>
		</div>
<?php include("_footer.php"); ?>
</center>
</body>
</html>