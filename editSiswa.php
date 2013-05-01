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
			  Tambah User
			</span></h2>
			<?php							
			 echo '<form id="formUser" name="formUser" method="post" action="SaveUser.php">';
			 echo "<input type='hidden' name='modeProses' id='modeProses' value='UBAH'>";	
			 
			$query = "SELECT USERNAME, PASSWORD, NAMA, JENIS FROM USER WHERE USERNAME='".$_REQUEST["username"]."'";
			$result = mysql_query($query);
			$baris=mysql_fetch_assoc($result);
		
					echo '<center>';
					echo '<div>';
					echo '<table width="600px" cellpadding="2" cellspacing="1" border="0" style="margin-top: 10px; margin-bottom: 10px;">';
						echo '<tr>';
							echo '<td class="log2" style="text-align:right" width="100px"><b>Nama Lengkap :</b></td>';
   						    echo '<td class="log2"><input type="text" id="edNama" name="edNama" size="50" value="'.$baris["NAMA"].'" /></td>';
						echo '</tr>';	
						echo '<tr>';
							echo '<td class="log2" style="text-align:right" width="100px"><b>Username :</b></td>';
   						    echo '<td class="log2"><input type="text" id="edUser" name="edUser" size="30" value="'.$baris["USERNAME"].'" readonly=true/></td>';
						echo '</tr>';												
					    echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top"><b>Password :</b></td>';
						  echo '<td class="log2"><input type="password" id="edPass" name="edPass" size="30" value="" />';
						  echo '<input type="hidden" name="rdJenis" value="'.$baris["JENIS"].'">';								
						  echo '</td>';
					    echo '</tr>';								
						echo '<tr>';
							echo '<td class="log2">&nbsp;</td>';
							echo '<td class="log2">';
								echo '<input id="btnOK" type="submit" name="btnOK" value="Simpan Perubahan" />';
							echo '</td>';
						echo '</tr>';
					echo '</table>';					
					echo '</div>';
					echo '</center>';
			
			echo '</form>';	
			?>
			<div class="meta">
				<br/>
				<center><a href="listSiswa.php">Kembali ke halaman daftar siswa</a></center>
			</div>
		</div>

<?php include("_footer.php"); ?>
</center>
</body>
</html>