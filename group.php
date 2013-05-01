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
			  Daftar Group Soal
			</span></h2>
			<center><table  border="1" cellpadding="5" cellspacing="0">
				<tr>
					<td class="judulTable" width="50px">
						Nomor
					</td>
					<td class="judulTable" width="500px">
						Nama Group
					</td>							
					<td class="judulTable" width="120px" colspan="2">					
						Action
					</td>
				</tr>
				<?php
					$query="SELECT KODE_GROUP, NAMA_GROUP FROM GROUP_SOAL ORDER BY KODE_GROUP ASC";
					$result=mysql_query($query);
					$i = 1;
					while($row=mysql_fetch_assoc($result)){

					  echo("<tr>");
					  echo "<form method='post' action='EditGroup.php'>";
					  echo('<td class="log2" valign="top" align="center">'. $i++ .".<input type='hidden' name='kode' id='kode' value='".$row['KODE_GROUP']."'></td>");
					  echo('<td class="log2" valign="top">'. $row['NAMA_GROUP'] ."<input type='hidden' name='nama' id='nama' value='".$row['NAMA_GROUP']."'></td>");					 				 
					  echo('<td class="log2" valign="top" style="border-right:0px;"><center>');
					  echo "<input type='submit' value='Ubah'></form></center></td>";
					  echo('<td style="border-left:0px;"><center><form method="post" action="SaveGroup.php"><input type="hidden" name="mode" id="mode" value="delete"><input type="hidden" name="kode_group" id="kode_group" value="'.$row["KODE_GROUP"].'"><input type="submit" value="Hapus" onclick="return confirm(\'Apakah Anda yakin?\');"></form></center></td>');
					  echo("</tr>");
					}
				?>
			</table>
			</center>
			<div class="meta">
				<br>
				<center><input type="button" value="Tambah Group" onClick="document.location.href='NewGroup.php';"> </center>
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