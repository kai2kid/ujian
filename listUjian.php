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
			  Daftar Ujian
			</span></h2>
			<center><table  border="1" cellpadding="5" cellspacing="0">
				<tr>					
					<td class="judulTable" width="165px">
						Nama
					</td>
					<td class="judulTable" width="100px">
						Tanggal
					</td>
					<td class="judulTable" width="165px">
						Ditambahkan oleh
					</td>					
					<td class="judulTable" width="130px" colspan="2">					
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
					
					//$query="SELECT KODE_UJIAN, NAMA_UJIAN, WAKTU_MULAI, PENGAJAR FROM UJIAN ORDER BY WAKTU_MULAI DESC";
					$query="SELECT KODE_UJIAN, NAMA_UJIAN, WAKTU_MULAI, PENGAJAR FROM UJIAN, HSOAL ";
					$query.=" WHERE HSOAL.KODE_SOAL=UJIAN.KODE_SOAL ";
					$query.=" AND ".$kondisi." ORDER BY WAKTU_MULAI DESC";
					$result=mysql_query($query);
					while($row=mysql_fetch_assoc($result)){

					  echo("<tr>");				
					  echo('<td class="log2" valign="top">'. $row['NAMA_UJIAN'] ."</td>");
					  echo('<td class="log2" valign="top">'. $row['WAKTU_MULAI'] ."</td>");
					  echo('<td class="log2" valign="top">'. $row['PENGAJAR'] ."</td>");					  
					  echo('<td class="log2" valign="middle"><center><form method="post" action="EditUjian.php"><input type="hidden" name="kode_ujian" value="'.$row["KODE_UJIAN"].'"><input type="submit" value="Ubah"></center></form></td>');
					  echo('<td style="border-left:0px;" valign="middle"><center><form method="post" action="SaveUjian.php"><input type="hidden" name="modeProses" id="modeProses" value="DELETE"><input type="hidden" name="kode_ujian" id="kode_ujian" value="'.$row["KODE_UJIAN"].'"><input type="submit" value="Hapus" onclick="return confirm(\'Apakah Anda yakin?\');"></form></center></td>');
					  echo("</tr>");
					}
				?>
			</table>
			</center>
			<div class="meta">
				<br>
				<center><input type="button" value="Tambah Ujian" onClick="document.location.href='Ujian.php';"> </center>
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