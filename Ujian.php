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
<script src="anytime.js"></script>
<center>
<div id="header"></div>
		<div id="main" class="post">			
			<h2 class="title" ><span>
			   Detail Ujian
			</span></h2>
			<?php
				$kodeUjian = "";				
				$mode = "INSERT";
				/*if (isset($_REQUEST["kode_soal"]) || isset($_SESSION["kode_soal"])){
					$kodeSoal = $_REQUEST["kode_soal"];
					if ($kodeSoal == "") $kodeSoal = $_SESSION["kode_soal"];
					$mode = "UBAH";
				}*/								
				
			 echo '<form id="formUjian" name="formUjian" method="post" action="SaveUjian.php">';
			 echo "<input type='hidden' name='modeProses' id='modeProses' value='".$mode."'>";
			 echo "<input type='hidden' name='kdujian' id='kdujian' value='".$kodeUjian."'>";
			?>
					<center>
					<div>
					<table width="600px" cellpadding="2" cellspacing="1" border="0" style="margin-top: 10px; margin-bottom: 10px;">
						<tr>
							<td class="log2" style="text-align:right" width="200px"><b>Nama Ujian :</b></td>								
   						    <td class="log2">
							<input type="text" id="edNama" name="edNama" size="45" value="" />								
							</td>
						</tr>
						<tr>
							<td class="log2" style="text-align:right" width="200px"><b>Mode :</b></td>								
   						    <td class="log2">
								<select name="mode" id="mode">
									<option value="0">Ujian</option>
									<option value="1">Latihan</option>
								</select>
							</td>
						</tr>						
						<tr>
							<td class="log2" align="right" width="200px"><b>Soal :</b></td>								
   						    <td class="log2">
								<select name="cbSoal" id="cbSoal">
									<?php
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
										while($row=mysql_fetch_assoc($result)){
											echo "<option value='".$row["KODE_SOAL"]."'>".$row["NAMA_SOAL"]."</option>";
										}
									?>
								</select>
							</td>
						</tr>							    											
						<tr>
						  <td class="log2" style="text-align:right" valign="top"><b>Waktu Mulai :</b></td>
						  <td>
							<input type="text" style="width: 125px;" name="waktuMulai" id="waktuMulai" value="" onclick="displayCalendarSelectBox(document.forms[0].year2,document.forms[0].month2,document.forms[0].day2,document.forms[0].hour2,document.forms[0].minute2,this)"/>									   
							<script>$('#waktuMulai').AnyTime_picker();</script>
							</td>
					    </tr>
						<tr>
						  <td class="log2" style="text-align:right" valign="top"><b>Waktu Berakhir :</b></td>
						  <td>
							<input type="text" style="width: 125px;" name="waktuAkhir" id="waktuAkhir" value="" />
							<script>$('#waktuAkhir').AnyTime_picker();</script>
							</td>
					    </tr>
						<tr>
						  <td class="log2" style="text-align:right" valign="top"><b>Jumlah Soal Per Halaman :</b></td>
						  <td>
							<input type="text" style="width: 70px;" id="JumSoal"  name="JumSoal" value="5" />							       
							</td>
					    </tr>
						<tr>
						  <td class="log2" style="text-align:right" valign="top"><b>Jenis Random :</b></td>
						  <td class="log2">
							<input type="radio" name="rdRandom" value="soal" checked>&nbsp;Random Soal &nbsp;<input type="radio" name="rdRandom" value="group">&nbsp;Random Per Group					       
						  </td>
					    </tr>
						<tr>
						  <td class="log2" style="text-align:right" valign="top"><b>Rumus Penilaian :</b></td>
						  <td>
							N = <input type="text" style="width: 150px;" id="rumus" name="rumus" value="B/J*100" />							       
							</td>
					    </tr>
						<tr>
						  <td class="log2" style="text-align:right" valign="top">&nbsp;</td>
						  <td class="log2">
								N = Nilai <br>
								B = Jumlah Benar <br>
								S = Jumlah Salah <br>
								J = Jumlah Soal	 <br>
						  </td>
					    </tr>
						<tr>
						  <td class="log2" style="text-align:right" valign="top"><b>Durasi Per Soal :</b></td>
						  <td class="log2">
							<input type="text" style="width: 70px;" id="DurSoal" name="DurSoal" value="" />							       
							&nbsp; menit</td>
					    </tr>
						<tr>
						  <td class="log2" style="text-align:right" valign="top"><b>Durasi Ujian :</b></td>
						  <td class="log2">
							<input type="text" style="width: 70px;" id="DurUjian" name="DurUjian" value="" />							       
							&nbsp; menit</td>
					    </tr>
						<tr>
						  <td class="log2" style="text-align:right" valign="top"><b>Nama Pengajar :</b></td>
						  <td>
							<input type="text" style="width: 250px;" id="Pengajar" name="Pengajar" value="" />							       
							</td>
					    </tr>
						<tr>
						  <td class="log2" style="text-align:right" valign="top"><b>Peserta Ujian :</b></td>
						  <td class="log2">
							<textarea name='peserta' id='peserta' rows="15" cols="25" style="resize: none;"></textarea>					       
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
				<center><a href="listUjian.php">Kembali ke halaman daftar ujian</a></center>
			</div>
		</div>
<?php include("_footer.php"); ?>
</center>
</body>
</html>