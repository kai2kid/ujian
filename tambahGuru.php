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
			Tambah Guru
			</span></h2>
			<?php				
			if (isset($_REQUEST["user"]))
			{	
				echo "<script>alert('Username ".$_REQUEST["user"].", telah terdaftar!');</script>";
			}
			 echo '<form id="formUser" name="formUser" method="post" action="SaveUser.php">';
			 echo "<input type='hidden' name='modeProses' id='modeProses' value='INSERT'>";			 
			?>
					<center>
					<div>
					<table width="600px" cellpadding="2" cellspacing="1" border="0" style="margin-top: 10px; margin-bottom: 10px;">
						<tr>
							<td class="log2" style="text-align:right" width="120px"><b>Nama Guru :</b></td>								
   						    <td class="log2"><input type="text" id="edNama" name="edNama" size="50" value="" /></td>
						</tr>	
						<tr>
							<td class="log2" style="text-align:right" width="120px"><b>Username :</b></td>								
   						    <td class="log2"><input type="text" id="edUser" name="edUser" size="30" value="" /></td>
						</tr>												
					    <tr>
						  <td class="log2" style="text-align:right" valign="top"><b>Password :</b></td>						  
						  <td class="log2"><input type="password" id="edPass" name="edPass" size="30" value="" />
						    <input type="hidden" id="rdJenis" name="rdJenis" value="G">
						  </td>							
					    </tr>	
						<tr>
							<td class="log2" style="text-align:right" width="120px" valign="top"><b>Mata Pelajaran :</b></td>								
   						    <td class="log2">
							<?php
								$query = "SELECT KODE_MAPEL, NAMA_MAPEL FROM MAPEL ORDER BY KODE_MAPEL ASC";
								$result = mysql_query($query);
								while($baris=mysql_fetch_assoc($result))
								{
									echo "<input type='checkbox' name='mapel_".$baris["KODE_MAPEL"]."' id='mapel_".$baris["KODE_MAPEL"]."'>&nbsp;&nbsp;".$baris["NAMA_MAPEL"]."<br>";
								}
							?>
							</td>
						</tr>		
						<tr>
							<td class="log2">&nbsp;</td>
							<td class="log2">
								<input id="btnOK" type="submit" name="btnOK" value="Simpan" />
							</td>
						</tr>
					</table>					
					</div>
					</center>

			</form>	
			<div class="meta">
				<br/>
				<center><a href="listUser.php">Kembali ke halaman daftar guru</a></center>
			</div>
		</div>
<?php include("_footer.php"); ?>
</center>
</body>
</html>