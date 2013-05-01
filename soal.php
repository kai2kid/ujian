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
		unset($_SESSION["kode_mapel"]);
	?>
		<div id="main" class="post">		
			<h2 class="title" ><span>
			Daftar Soal
			</span></h2>
			<center><table  border="1" cellpadding="5" cellspacing="0">
				<tr>					
					<td class="judulTable" width="200px">
						Nama Soal
					</td>
					<td class="judulTable" width="90px">
						Tgl. Ujian
					</td>
					<td class="judulTable" width="150px">
						Ditambahkan oleh
					</td>					
					<td class="judulTable" width="100px" colspan="2">					
						Action
					</td>
				</tr>
				<?php
					$query = "SELECT KODE_MAPEL FROM MAPEL_USER WHERE USERNAME='".$_SESSION["USER"]."' ORDER BY KODE_MAPEL ASC";
					$result = mysql_query($query);					
					$kondisi = "(1=0 ";
					while($row=mysql_fetch_assoc($result))
					{
						$kondisi.= " OR KODE_MAPEL='".$row["KODE_MAPEL"]."'";						
					}
					$kondisi.=")";
								
					//HANYA MENAMPILKAN YANG SESUAI DENGAN KODE_MAPEL nya=======		
					
					$query="SELECT KODE_SOAL, NAMA_SOAL, TGL_INSERT, INSERT_BY FROM HSOAL ";					
					$query.=" WHERE " . $kondisi;
					$query.=" ORDER BY KODE_SOAL DESC";					
					$result=mysql_query($query);
					while($row=mysql_fetch_assoc($result)){

					  echo("<tr>");					 
					  echo('<td class="log2" valign="top">'. $row['NAMA_SOAL'] ."&nbsp;</td>");
					  echo('<td class="log2" valign="top">'. $row['TGL_INSERT'] ."&nbsp;</td>");
					  echo('<td class="log2" valign="top">'. $row['INSERT_BY'] ."&nbsp;</td>");					  
					  echo('<td class="log2" valign="middle"><center><form method="post" action="EditSoal.php"><input type="hidden" name="kode_soal" value="'.$row["KODE_SOAL"].'"><input type="submit" value="Ubah"></center></form></td>');
					  echo('<td style="border-left:0px;" valign="middle"><center><form method="post" action="SaveSoal.php"><input type="hidden" name="mode" id="mode" value="delete"><input type="hidden" name="kode_soal" id="kode_soal" value="'.$row["KODE_SOAL"].'"><input type="submit" value="Hapus" onclick="return confirm(\'Apakah Anda yakin?\');"></form></center></td>');
					  echo("</tr>");
					}
				?>
			</table>
			</center>
			<div class="meta">
				<br>
				<center><input type="button" value="Tambah Soal" onClick="document.location.href='NewSoal.php';"> </center>
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