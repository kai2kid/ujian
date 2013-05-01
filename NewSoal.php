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
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="ckfinder/ckfinder.js"></script>
<center>
<div id="header"></div>
		<div id="main" class="post">			
			<h2 class="title" ><span>TAMBAH SOAL</span></h2>
      <form id="formSoal" name="formSoal" method="post" action="EditNewSoal.php">
      <input type='hidden' name='modeProses' id='modeProses' value='INSERT'>
      <input type='hidden' name='kdsoal' id='kdsoal' value=''>
					<center>
					<div>
					<table width="600px" cellpadding="2" cellspacing="1" border="0" style="margin-top: 10px; margin-bottom: 10px;">
						<tr>
							<td class="log2" style="text-align:right" width="250px"><b>Mata Pelajaran :</b></td>								
   						<td class="log2">													
							<?php
								$query="SELECT KODE_MAPEL, NAMA_MAPEL FROM MAPEL ";								
								if (isset($_SESSION["kode_mapel"]))
								{
									$query .= " WHERE KODE_MAPEL='".$_SESSION["kode_mapel"]."' ORDER BY NAMA_MAPEL ASC";
									$result = mysql_query($query);	
									$row=mysql_fetch_assoc($result);
									echo $row["NAMA_MAPEL"];
									echo "<input type='hidden' name='cbMapel' id='cbMapel' value='".$row["KODE_MAPEL"]."'>";
								} else {
									echo '<select name="cbMapel" id="cbMapel">';
									$query .= " ORDER BY NAMA_MAPEL ASC";
									$result = mysql_query($query);	
									while($row=mysql_fetch_assoc($result)){
										echo "<option value='".$row["KODE_MAPEL"]."'>".$row["NAMA_MAPEL"]."</option>";
									}
									echo "</select>";
								}																							
							?>								
							</td>
						</tr>			
						<tr>
							<td class="log2" style="text-align:right"><b>Nama Soal :</b></td>								
   						    <td class="log2">
							<?php
								if (isset($_SESSION["kode_soal"])) {
									echo $_SESSION["nama_soal"];
								} else {								
									echo '<input type="text" id="edNama" name="edNama" size="45" value="" />';
								}
							?>
							</td>
						</tr>						
						<tr>
							<td class="log2">&nbsp;</td>
							<td class="log2">
								<input id="btnOK" type="button" name="btnOK" value="Lanjut >>" onclick="formSoal.submit();" /><!--onclick="if (document.getElementById('cbGroup').value!='---') {formSoal.submit();} else {alert('Group soal tidak boleh kosong!');}"/>-->
							</td>
						</tr>
					</table>					
					</div>
					</center>
			</form>	
			<div class="meta">				
				<center><a href="soal.php">Kembali ke halaman soal</a></center>
			</div>
		</div>
<?php include("_footer.php"); ?>
</center>
</body>
</html>