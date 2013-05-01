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
<script>
  function moveTo(lok) {
    if (lok != "") {      
      $.post('changeKode.php', {kode:lok}, function(data) {
        $('#isiNilai').html(data);
		$('#nama_ujian').val($("#cbUjian option:selected").text());				
		$('#hasil_nilai').val(data);
      });
    } else {
      $('#isiNilai').html('');
    }
  }
</script>
<center>
<div id="header"></div>
	<?php
		unset($_SESSION["kode_soal"]);
	?>
		<div id="main" class="post">					
			  <form method="post" action="createExcel.php" target="_blank">
			  <h2 class="title" ><span>
			  Nilai Ujian &nbsp;&nbsp;
			<select id="cbUjian" name="cbUjian" onchange="moveTo(this.value)">
      <option value=''> ----- </option>";
			<?php
				$query="SELECT KODE_UJIAN, NAMA_UJIAN FROM UJIAN ORDER BY NAMA_UJIAN ASC";
				$result = mysql_query($query);
				while($baris=mysql_fetch_assoc($result)){										
					echo "<option value='".$baris["KODE_UJIAN"]."'>".$baris["NAMA_UJIAN"]."</option>";
				}			
			?>
			</select>			
			</span>
			
			</h2>
			<center>			
			<div id="isiNilai"></div>
			</center>
			<div class="meta">								
				<br/>
				<table width="560px">
				<tr>
					<td align="right">										
							<input type="hidden" name="nama_ujian" id="nama_ujian" value="">
							<input type="hidden" name="hasil_nilai" id="hasil_nilai" value="">
							<input type="submit" value="Create Excel">						
					</td>
				</tr>
				</table>	
				</form>				
			<br/>
				
				<center><a href="indexguru.php">Kembali ke halaman index</a></center>
			</div>
		</div>
<?php include("_footer.php"); ?>
</center>
</body>
</html>