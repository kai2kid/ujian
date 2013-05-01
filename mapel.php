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
		<div id="main" class="post">		
			<h2 class="title" ><span>
			  Daftar Mata Pelajaran
			</span></h2>
			<center><table  border="1" cellpadding="5" cellspacing="0">
				<tr>
					<td class="judulTable" width="50px">
						Nomor
					</td>
					<td class="judulTable" width="500px">
						Nama Mata Pelajaran
					</td>							
					<td class="judulTable" width="120px" colspan="2">					
						Action
					</td>
				</tr>
				<?php
					$query="SELECT KODE_MAPEL, NAMA_MAPEL FROM MAPEL ORDER BY NAMA_MAPEL ASC";
					$result=mysql_query($query);
					$i = 1;
					while($row=mysql_fetch_assoc($result)){

					  echo("<tr>");
					  echo "<form method='post' action='EditMapel.php'>";
					  echo('<td class="log2" valign="top" align="center">'. $i++ .".<input type='hidden' name='kode' id='kode' value='".$row['KODE_MAPEL']."'></td>");
					  echo('<td class="log2" valign="top">'. $row['NAMA_MAPEL'] ."<input type='hidden' name='nama' id='nama' value='".$row['NAMA_MAPEL']."'></td>");					 				 
					  echo('<td class="log2" valign="top" style="border-right:0px;"><center>');
					  echo "<input type='submit' value='Ubah'></form></center></td>";
					  echo('<td style="border-left:0px;"><center><form method="post" action="SaveMapel.php"><input type="hidden" name="mode" id="mode" value="delete"><input type="hidden" name="kode_mapel" id="kode_mapel" value="'.$row["KODE_MAPEL"].'"><input type="submit" value="Hapus" onclick="return confirm(\'Apakah Anda yakin?\');"></form></center></td>');
					  echo("</tr>");
					}
				?>
			</table>
			</center>
			<div class="meta">
				<br>
				<center><input type="button" value="Tambah Mata Pelajaran" onClick="document.location.href='NewMapel.php';"> </center>
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