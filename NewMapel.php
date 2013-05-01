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
			<h2 class="title" ><span>Tambah Mata Pelajaran</span></h2>
			<?php				
				echo '<form id="formMapel" name="formMapel" method="post" action="SaveMapel.php">';	
				echo "<input type='hidden' name='mode' id='mode' value='insert'>";			 
			?>
					<center>
					<div>
					<table width="600px" cellpadding="1" cellspacing="1" border="0" style="margin-top: 10px; margin-bottom: 10px;">
						<tr>
							<td class="log2" style="text-align:right" ><b>Nama Mata Pelajaran :</b></td>								
   						    <td class="log2"><input id="nama_mapel" type="text" name="nama_mapel" size="45" value=""/></td>
						</tr>																	    				
						<tr>
							<td class="log2">&nbsp;</td>
							<td class="log2">
								<input id="btnOK" type="submit" name="btnOK" value="Tambahkan Mata Pelajaran" />
							</td>
						</tr>
					</table>
					</div>
					</center>

			</form>			
			<div class="meta">
				<br/>
				<center><a href="mapel.php">Kembali ke halaman daftar mata pelajaran</a></center>
			</div>
		</div>
<?php include("_footer.php"); ?>
</center>
</body>
</html>