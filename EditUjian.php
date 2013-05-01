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
<link rel="stylesheet" type="text/css" href="anytime.css" />
<script src="jquery.js"></script>
<script src="anytime.js"></script>
<center>
<div id="header"></div>
		<div id="main" class="post">			
			<h2 class="title" ><span>Ubah Ujian</span></h2>
			<?php				
				echo '<form id="formUjian" name="formUjian" method="post" action="SaveUjian.php">';	
				echo "<input type='hidden' name='modeProses' id='modeProses' value='UBAH'>";			 
				echo "<input type='hidden' name='kode_ujian' id='kode_ujian' value='".$_REQUEST["kode_ujian"]."'>";	

				$query = "SELECT JENIS, KODE_UJIAN, NAMA_UJIAN, KODE_SOAL, WAKTU_MULAI, WAKTU_AKHIR, JUM_PERHALAMAN, RANDOM_SOAL, RANDOM_GROUP, RUMUS_NILAI, DURASI_PER_SOAL, DURASI_UJIAN, PENGAJAR FROM UJIAN WHERE KODE_UJIAN='".$_REQUEST["kode_ujian"]."'";				
				$result = mysql_query($query,$dbConn);
				$row=mysql_fetch_assoc($result);
				$row["NAMA_UJIAN"];
					echo "<center>";
					echo '<div>';
					echo '<table width="600px" cellpadding="2" cellspacing="1" border="0" style="margin-top: 10px; margin-bottom: 10px;">';
						echo '<tr>';
							echo '<td class="log2" style="text-align:right" width="200px"><b>Nama Ujian :</b></td>';
   						    echo '<td class="log2">';
							echo '<input type="text" id="edNama" name="edNama" size="45" value="'.$row["NAMA_UJIAN"].'" />';								
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							$select1 = "";
							$select2 = "";
							if ($row["JENIS"]=='0') {
								$select1 = "selected";
							} else {
								$select2 = "selected";
							}
							echo '<td class="log2" style="text-align:right" width="200px"><b>Mode :</b></td>';
   						    echo '<td class="log2">';
							echo '<select name="mode" id="mode">';
							echo '<option value="0" '.$select1.'>Ujian</option>';
							echo '<option value="1" '.$select2.'>Latihan</option>';
							echo '</select>';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td class="log2" style="text-align:right" width="100px"><b>Soal :</b></td>';
   						    echo '<td class="log2">';
								echo '<select name="cbSoal" id="cbSoal">';	

										$query = "SELECT KODE_MAPEL FROM MAPEL_USER WHERE USERNAME='".$_SESSION["USER"]."' ORDER BY KODE_MAPEL ASC";
										$result = mysql_query($query);					
										$kondisi = "(1=0 ";
										while($row=mysql_fetch_assoc($result))
										{
											$kondisi.= " OR KODE_MAPEL='".$row["KODE_MAPEL"]."'";						
										}
										$kondisi.=")";
										
										$query="SELECT KODE_SOAL, NAMA_SOAL FROM HSOAL ";					
										$query.=" WHERE " . $kondisi;
										$query.=" ORDER BY KODE_SOAL DESC";	
								
										//$query="SELECT KODE_SOAL, NAMA_SOAL FROM HSOAL ORDER BY NAMA_SOAL ASC";
										$result = mysql_query($query);
										while($baris=mysql_fetch_assoc($result)){
											$check = "";
											if ($baris["KODE_SOAL"]==$row["KODE_SOAL"]) $check = "selected";
											echo "<option value='".$baris["KODE_SOAL"]."' ".$check.">".$baris["NAMA_SOAL"]."</option>";
										}									
								echo '</select>';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top"><b>Waktu Mulai :</b></td>';
						  echo '<td>';
							echo '<input type="text" style="width: 125px;" name="waktuMulai" id="waktuMulai" value="'.$row["WAKTU_MULAI"].'" onclick="displayCalendarSelectBox(document.forms[0].year2,document.forms[0].month2,document.forms[0].day2,document.forms[0].hour2,document.forms[0].minute2,this)"/>';
							echo "<script>$('#waktuMulai').AnyTime_picker();</script>";
							echo "</td>";
					    echo '</tr>';
						echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top"><b>Waktu Berakhir :</b></td>';
						  echo '<td>';
							echo '<input type="text" style="width: 125px;" name="waktuAkhir" id="waktuAkhir" value="'.$row["WAKTU_AKHIR"].'" />';
							echo "<script>$('#waktuAkhir').AnyTime_picker();</script>";
							echo '</td>';
					    echo '</tr>';
						echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top"><b>Jumlah Soal Per Halaman :</b></td>';
						  echo '<td>';
							echo '<input type="text" style="width: 70px;" id="JumSoal"  name="JumSoal" value="'.$row["JUM_PERHALAMAN"].'" />';
							echo '</td>';
					    echo '</tr>';
						echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top"><b>Jenis Random :</b></td>';
						  echo '<td class="log2">';
						  $r_soal = "";
						  $r_group = "";
						  if ($row["RANDOM_SOAL"]==1) $r_soal = "checked";
						  if ($row["RANDOM_GROUP"]==1) $r_group = "checked";
							echo '<input type="radio" name="rdRandom" value="soal" '.$r_soal.'>&nbsp;Random Soal &nbsp;<input type="radio" name="rdRandom" value="group" '.$r_group.'>&nbsp;Random Per Group';					       
						  echo '</td>';
					    echo '</tr>';
						echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top"><b>Rumus Penilaian :</b></td>';
						  echo '<td>';
							echo 'N = <input type="text" style="width: 150px;" id="rumus" name="rumus" value="'.$row["RUMUS_NILAI"].'" />';
							echo '</td>';
					    echo '</tr>';
						echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top">&nbsp;</td>';
						  echo '<td class="log2">';
								echo 'N = Nilai <br>';
								echo 'B = Jumlah Benar <br>';
								echo 'S = Jumlah Salah <br>';
								echo 'J = Jumlah Soal <br>';
						  echo '</td>';
					    echo '</tr>';
						echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top"><b>Durasi Per Soal :</b></td>';
						  echo '<td class="log2">';
							echo '<input type="text" style="width: 70px;" id="DurSoal" name="DurSoal" value="'.$row["DURASI_PER_SOAL"].'" />';
							echo '&nbsp; menit</td>';
					    echo '</tr>';
						echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top"><b>Durasi Ujian :</b></td>';
						  echo '<td class="log2">';
							echo '<input type="text" style="width: 70px;" id="DurUjian" name="DurUjian" value="'.$row["DURASI_UJIAN"].'" />';
							echo '&nbsp; menit</td>';
					    echo '</tr>';
						echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top"><b>Nama Pengajar :</b></td>';
						  echo '<td>';
							echo '<input type="text" style="width: 250px;" id="Pengajar" name="Pengajar" value="'.$row["PENGAJAR"].'" />';
							echo '</td>';
					    echo '</tr>';
						echo '<tr>';
						  echo '<td class="log2" style="text-align:right" valign="top"><b>Peserta Ujian :</b></td>';
						  echo '<td class="log2">';
							echo "<textarea name='peserta' id='peserta' rows='15' cols='25' style='resize: none;'>";
								$query="SELECT KODE_UJIAN, USERNAME FROM PESERTA_UJIAN WHERE KODE_UJIAN='".$row["KODE_UJIAN"]."' ORDER BY USERNAME ASC";
								$result = mysql_query($query);
								while($baris=mysql_fetch_assoc($result)){																		
									echo $baris["USERNAME"]."\n";
								}		
							echo "</textarea>";
						  echo '</td>';
					    echo '</tr>	';
						echo '<tr>';
							echo '<td class="log2">&nbsp;</td>';
							echo '<td class="log2">';
								echo '<input id="btnOK" type="submit" name="btnOK" value="Simpan" />';
							echo '</td>';
						echo '</tr>';
					echo '</table>';					
					echo '</div>';
					echo '</center>';
			?>
			</form>			
			<div class="meta">
				<br/>
				<center><a href="listUjian.php">Kembali ke halaman daftar ujian</a></center>
			</div>
		</div>
  <?php include("_footer.php"); ?>
  </center>
</body>
</html>