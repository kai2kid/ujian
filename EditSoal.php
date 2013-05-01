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
  $soal_kode = $_REQUEST['kode_soal'];
  $soal_no = 1;
  if (isset($_REQUEST['no']) && $_REQUEST['no'] > 0) $soal_no = $_REQUEST['no'];
  $noSoal = $soal_no;
 ?>
 <html xmlns="http://www.w3.org/1999/xhtml">
 <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
 <script type="text/javascript" src="ckfinder/ckfinder.js"></script>
 <script type="text/javascript" src="jquery.js"></script>
 <script>
	var oldVal = '';
	$(document).ready(function() {
		$("#cbNo").change(function() {
      document.location = 'EditSoal.php?kode_soal=<?php echo $soal_kode; ?>&no='+$("#cbNo").val();
		});
	});
 </script>
<?php include("_head.php"); ?>
<body>

<div id="header"></div>
		<div id="main" class="post">			
			<h2 class="title" ><span>Detail Soal</span></h2>
			<div style="padding-left:15px; line-height: 30px; padding-bottom: 10px;">
			<form method="post" action="EditNewSoal.php">
      <input type="hidden" id="modeProses" name="modeProses" value="UBAH">      
			<input type="hidden" name="kode_soal" value="<?php echo $soal_kode; ?>">
			<?php
        $query="
          SELECT DSOAL.PEMBAHASAN, DSOAL.NO_URUT, DSOAL.SOAL, DSOAL.KODE_SOAL, DSOAL.PILA, DSOAL.PILB, DSOAL.PILC, DSOAL.PILD, DSOAL.PILE, DSOAL.JAWABAN, GROUP_SOAL.NAMA_GROUP 
          FROM DSOAL, GROUP_SOAL 
          WHERE GROUP_SOAL.KODE_GROUP=DSOAL.KODE_GROUP 
            AND DSOAL.KODE_SOAL='".$_REQUEST["kode_soal"]."' 
            AND DSOAL.NO_URUT='$soal_no'";
        $result = mysql_query($query);    
        $row_soal=mysql_fetch_assoc($result);
      
				//MENAMPILKAN JUDUL================
				$query="SELECT NAMA_SOAL FROM HSOAL WHERE KODE_SOAL='$soal_kode' ";
				$result = mysql_query($query);
				$baris=mysql_fetch_assoc($result);
				echo "<center><label style='font-size:12pt; font-weight:bold;'>".strtoupper($baris["NAMA_SOAL"])."</label></center>";
				
        //MENAMPILKAN NOMOR SOAL===========
        $query="SELECT NO_URUT FROM DSOAL WHERE KODE_SOAL='$soal_kode' ORDER BY NO_URUT ASC ";            
        $result = mysql_query($query);
        $noUrut = 0;
        echo "Soal Nomor &nbsp;:&nbsp;<select name='cbNo' id='cbNo' >";
        while($row=mysql_fetch_assoc($result)){
          $noUrut = $row["NO_URUT"];
          if ($noUrut == $soal_no) { $sel = "selected"; } else { $sel = ""; }
          echo "<option value='$noUrut' $sel>".$noUrut."</option>";
        }
        $noUrut++;
        if ($noUrut == $soal_no) { $sel = "selected"; } else { $sel = ""; }
        echo "<option value='$noUrut' $sel>".$noUrut." (baru)</option>";
        echo "</select><br>";
                
        echo "<script language='javascript'>oldVal = $('#cbNo').val();</script>";

				//MENAMPILKAN GROUP SOAL===========
        $query="SELECT KODE_GROUP FROM DSOAL WHERE KODE_SOAL='".$_REQUEST["kode_soal"]."' AND NO_URUT = '$soal_no'";
        $result = mysql_query($query);
        $row=mysql_fetch_assoc($result);
        $kodeGroup = $row['KODE_GROUP'];
        
				$query="SELECT KODE_GROUP, NAMA_GROUP FROM GROUP_SOAL ORDER BY NAMA_GROUP ASC";
				$result = mysql_query($query);
				echo "Group Soal &nbsp; :&nbsp;<select name='cbGroup' id='cbGroup'>";
        echo "<option value='0'>---</option>";
				while($row=mysql_fetch_assoc($result)){
          if ($row['KODE_GROUP'] == $kodeGroup) { $sel = "selected"; } else { $sel = ""; }
					echo "<option value='".$row['KODE_GROUP']."' $sel>".$row['NAMA_GROUP']."</option>";
				}
				echo "</select><br>";
								
        echo "<div id='isiSoal'>";      

				//MENAMPILKAN BACAAN===============
				
				$query="SELECT ISI_BACAAN, KODE_BACAAN, START_NUM, END_NUM FROM BACAAN WHERE KODE_SOAL='$soal_kode' AND START_NUM<=$soal_no AND $soal_no<=END_NUM ";						
				$result = mysql_query($query);		
				$row=mysql_fetch_assoc($result);
				$jum = mysql_num_rows($result);
        $bacaan_start = $soal_no;
        $bacaan_end = $soal_no;
        $bacaan_kode = "";
				if ($jum>0){
          $bacaan_start = $row["START_NUM"];
          $bacaan_end = $row["END_NUM"];
          $bacaan_kode = $row["KODE_BACAAN"];
				}
        echo '<br>';
        echo '[BACAAN] untuk soal nomor ';
        echo "<input type='text' name='edStart' id='edStart' value='$bacaan_start' size='1'>&nbsp;-&nbsp;";
        echo "<input type='text' name='edEnd' id='edEnd' value='$bacaan_end' size='1'>";
        echo "<input type='hidden' name='kode_bacaan' id='kode_bacaan' value='$bacaan_kode'>";
        echo '<br>';
        echo '<textarea id="bacaan_'.$soal_kode.'" name="bacaan_'.$soal_kode.'" rows="3" cols="45" style="resize: none;">'.$row["ISI_BACAAN"].'</textarea><br>';
        echo '<script type="text/javascript">
          editorBacaan = CKEDITOR.replace("bacaan_'.$soal_kode.'", {height: 150, width: 750});
          CKFinder.setupCKEditor(editorBacaan, "ckfinder");
        </script>';
        
				//MENAMPILKAN SOAL=================				
        $row = $row_soal;
				echo '[SOAL]<br>';
				echo '<textarea id="soal_'.$row["KODE_SOAL"].'_'.$row["NO_URUT"].'" name="soal_'.$row["KODE_SOAL"].'_'.$row["NO_URUT"].'" rows="3" cols="45" style="resize: none;">'.$row["SOAL"].'</textarea><br>';
				echo '<script type="text/javascript">';
				echo 'editorSoal = CKEDITOR.replace( "soal_'.$row["KODE_SOAL"].'_'.$row["NO_URUT"].'", {height: 120, width: 750});';				
				echo 'CKFinder.setupCKEditor(editorSoal, "ckfinder");';
				echo '</script>';						
				
				echo "<table cellpadding='2' cellspacing='3' style='width:875px;'>";
				echo "<tr>";								
				echo "<td>";
				//A===================
				echo "&nbsp;&nbsp;&nbsp;<input type='radio' value='1' name='pil_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."'";
				if ($row["JAWABAN"]=="1") { echo "checked='checked'"; }
				echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN A]</label><textarea name='isiA_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' id='isiA_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' rows='3' cols='45' style='resize: none;'>".$row["PILA"]."</textarea><br>";
				echo "<script type='text/javascript'>";
				echo "isiA = CKEDITOR.replace( 'isiA_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."', {height: 75, width: 400});";
				echo 'CKFinder.setupCKEditor(isiA, "ckfinder");';
				echo "</script>";
				echo "</td>";				
				echo "<td>";
				//D===================					
				echo "&nbsp;&nbsp;&nbsp;<input type='radio' value='4' name='pil_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."'";					
				if ($row["JAWABAN"]=="4") { echo "checked='checked'"; }										
				echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN D]</label><textarea name='isiD_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' id='isiD_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' rows='3' cols='45' style='resize: none;'>".$row["PILD"]."</textarea><br>";
				echo "<script type='text/javascript'>";
				echo "isiD = CKEDITOR.replace( 'isiD_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."', {height: 75, width: 400});";
				echo 'CKFinder.setupCKEditor(isiD, "ckfinder");';
				echo "</script>";	
				echo "</td>";
				echo "</tr>";
				
				echo "<tr>";								
				echo "<td>";
				//B===================
				echo "&nbsp;&nbsp;&nbsp;<input type='radio' value='2' name='pil_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."'";
				if ($row["JAWABAN"]=="2") { echo "checked='checked'"; }									
				echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN B]</label><textarea name='isiB_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' id='isiB_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' rows='3' cols='45' style='resize: none;'>".$row["PILB"]."</textarea><br>";
				echo "<script type='text/javascript'>";
				echo "isiB = CKEDITOR.replace( 'isiB_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."', {height: 75, width: 400});";
				echo 'CKFinder.setupCKEditor(isiB, "ckfinder");';
				echo "</script>";		
				echo "</td>";				
				echo "<td>";
				//E===================
				echo "&nbsp;&nbsp;&nbsp;<input type='radio' value='5' name='pil_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."'";
				if ($row["JAWABAN"]=="5") { echo "checked='checked'"; }					
				echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN E]</label><textarea name='isiE_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' id='isiE_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' rows='3' cols='45' style='resize: none;'>".$row["PILE"]."</textarea><br>";
				echo "<script type='text/javascript'>";
				echo "isiE = CKEDITOR.replace( 'isiE_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."', {height: 75, width: 400});";
				echo 'CKFinder.setupCKEditor(isiE, "ckfinder");';
				echo "</script>";	
				echo "</td>";
				echo "</tr>";													
				
				echo "<tr>";								
				echo "<td>";
				//C===================
				echo "&nbsp;&nbsp;&nbsp;<input type='radio' value='3' name='pil_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."'";
				if ($row["JAWABAN"]=="3") { echo "checked='checked'"; }					
				echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN C]</label><textarea name='isiC_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' id='isiC_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' rows='3' cols='45' style='resize: none;'>".$row["PILC"]."</textarea><br>";
				echo "<script type='text/javascript'>";
				echo "isiC = CKEDITOR.replace( 'isiC_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."', {height: 75, width: 400});";
				echo 'CKFinder.setupCKEditor(isiC, "ckfinder");';
				echo "</script>";		
				echo "</td>";
				echo "&nbsp;";
				echo "<td>";
				echo "</td>";
				echo "</tr>";											
				echo "</table>";
				
				//MENAMPILKAN PEMBAHASAN============											
				echo '[PEMBAHASAN]<br>';
				echo '<textarea id="pembahasan_'.$row["KODE_SOAL"].'_'.$row["NO_URUT"].'" name="pembahasan_'.$row["KODE_SOAL"].'_'.$row["NO_URUT"].'" rows="3" cols="45" style="resize: none;">'.$row["PEMBAHASAN"].'</textarea><br>';
				echo '<script type="text/javascript">';
				echo 'editorSoal = CKEDITOR.replace( "pembahasan_'.$row["KODE_SOAL"].'_'.$row["NO_URUT"].'", {height: 120, width: 750});';				
				echo 'CKFinder.setupCKEditor(editorSoal, "ckfinder");';
				echo '</script>';			
				
				echo "</div>";			
			?>
			<br>
      <div style='text-align: center;'>
        <input type="submit" value="Simpan Perubahan" ><!--nclick="alert($('#soal_6_1').val());">-->
      </div>
        
			</form>
			</div>	
				<div class="meta">
					<br/>
					<center><a href="soal.php">Kembali ke halaman soal</a></center>
				</div>
		</div>
<?php include("_footer.php"); ?>

<?php
	if (isset($_REQUEST["sukses"]))
	{
		echo "<script>alert('Perubahan soal nomor ".$_REQUEST["sukses"]." telah berhasil!'); </script>";
	}
?>
</body>
</html>