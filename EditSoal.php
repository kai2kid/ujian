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
  $soal_no = '0';
  if (isset($_REQUEST["sukses"])) {
    $soal_no = $_REQUEST["sukses"];
  } else {
    if (isset($_REQUEST['no']) && $_REQUEST['no'] > 0) $soal_no = $_REQUEST['no'];
  }

 ?>
 <html xmlns="http://www.w3.org/1999/xhtml">
 <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
 <script type="text/javascript" src="ckfinder/ckfinder.js"></script>
 <script type="text/javascript" src="jquery.js"></script>
 <script type="text/javascript" src="script/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<?php
/*/
      plugins: [
       "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
       "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
       "save table contextmenu directionality emoticons template paste textcolor"
     ]
/*/
?>
 <script>
	$(document).ready(function() {
		$("#cbNo").change(function() {
      document.location = 'EditSoal.php?kode_soal=<?php echo $soal_kode; ?>&no='+$("#cbNo").val();
		});
    tinymce.init({
      selector: "textarea",
      theme : "advanced",
      theme_advanced_buttons1 : "fontselect,fontsizeselect,formatselect,bold,italic,underline,strikethrough,separator,sub,sup,separator,cut,copy,paste,undo,redo",
      theme_advanced_buttons2 : "justifyleft,justifycenter,justifyright,justifyfull,separator,numlist,bullist,outdent,indent,separator,forecolor,backcolor,separator,hr,link,unlink,image,media,table,code,separator,asciimath,asciimathcharmap,asciisvg",
      theme_advanced_buttons3 : "",
      theme_advanced_fonts : "Arial=arial,helvetica,sans-serif,Courier New=courier new,courier,monospace,Georgia=georgia,times new roman,times,serif,Tahoma=tahoma,arial,helvetica,sans-serif,Times=times new roman,times,serif,Verdana=verdana,arial,helvetica,sans-serif",
      theme_advanced_toolbar_location : "top",
      theme_advanced_toolbar_align : "left",
      theme_advanced_statusbar_location : "bottom",
      plugins : 'asciimath,asciisvg,table,inlinepopups,media',
     
      AScgiloc : 'http://www.imathas.com/editordemo/php/svgimg.php',            //change me  
      ASdloc : 'http://www.imathas.com/editordemo/jscripts/tiny_mce/plugins/asciisvg/js/d.svg'  //change me    
      
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
      <input type="hidden" id="modeProses" name="modeProses" value="SOAL">      
			<input type="hidden" name="kode_soal" value="<?php echo $soal_kode; ?>">
			<?php
        $query="
          SELECT PEMBAHASAN, NO_URUT, SOAL, KODE_SOAL, PILA, PILB, PILC, PILD, PILE, JAWABAN
          FROM DSOAL
          WHERE KODE_SOAL='$soal_kode'  
            AND NO_URUT='$soal_no'";
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
        if ('0' == $soal_no) { $sel = "selected"; } else { $sel = ""; }
        echo "<option value='0' $sel>".$noUrut." (baru)</option>";
        echo "</select><br>";
                
        echo "<script language='javascript'>oldVal = $('#cbNo').val();</script>";

				//MENAMPILKAN GROUP SOAL===========
        $query="SELECT KODE_GROUP FROM DSOAL WHERE KODE_SOAL='$soal_kode' AND NO_URUT = '$soal_no'";
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
				
        $bacaan_start = $soal_no;
        $bacaan_end = $soal_no;          
        $bacaan_kode = "";
        if ($soal_no == "0") {
          $bacaan_start = $noUrut;
          $bacaan_end = $noUrut;          
        }
        $query="SELECT ISI_BACAAN, KODE_BACAAN, START_NUM, END_NUM FROM BACAAN WHERE KODE_SOAL='$soal_kode' AND START_NUM<=$bacaan_start AND $bacaan_start<=END_NUM ";						
//        echo $query;
				$result = mysql_query($query);		
				$row=mysql_fetch_assoc($result);
				$jum = mysql_num_rows($result);
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
        echo '<textarea id="bacaan_'.$soal_kode.'" name="bacaan_'.$soal_kode.'" rows="3" cols="45">'.$row["ISI_BACAAN"].'</textarea><br>';
        
				//MENAMPILKAN SOAL=================				
        $row = $row_soal;
				echo '[SOAL]<br>';
				echo '<textarea id="soal_'.$soal_kode.'_'.$soal_no.'" name="soal_'.$soal_kode.'_'.$soal_no.'" rows="3" cols="45" style="resize: none;">'.$row["SOAL"].'</textarea><br>';
				
				echo "<table cellpadding='2' cellspacing='3' style='width:875px;'>";
				echo "<tr>";								
				echo "<td>";
				//A===================
				echo "&nbsp;&nbsp;&nbsp;<input required type='radio' value='1' name='pil_".$soal_kode."_".$soal_no."'";
				if ($row["JAWABAN"]=="1") { echo "checked='checked'"; }
				echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN A]</label><textarea name='isiA_".$soal_kode."_".$soal_no."' id='isiA_".$soal_kode."_".$soal_no."' rows='3' cols='45' style='resize: none;'>".$row["PILA"]."</textarea><br>";
				echo "</td>";				
				echo "<td>";
				//D===================					
				echo "&nbsp;&nbsp;&nbsp;<input required type='radio' value='4' name='pil_".$soal_kode."_".$soal_no."'";					
				if ($row["JAWABAN"]=="4") { echo "checked='checked'"; }										
				echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN D]</label><textarea name='isiD_".$soal_kode."_".$soal_no."' id='isiD_".$soal_kode."_".$soal_no."' rows='3' cols='45' style='resize: none;'>".$row["PILD"]."</textarea><br>";
				echo "</td>";
				echo "</tr>";
				
				echo "<tr>";								
				echo "<td>";
				//B===================
				echo "&nbsp;&nbsp;&nbsp;<input required type='radio' value='2' name='pil_".$soal_kode."_".$soal_no."'";
				if ($row["JAWABAN"]=="2") { echo "checked='checked'"; }									
				echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN B]</label><textarea name='isiB_".$soal_kode."_".$soal_no."' id='isiB_".$soal_kode."_".$soal_no."' rows='3' cols='45' style='resize: none;'>".$row["PILB"]."</textarea><br>";
				echo "</td>";				
				echo "<td>";
				//E===================
				echo "&nbsp;&nbsp;&nbsp;<input required type='radio' value='5' name='pil_".$soal_kode."_".$soal_no."'";
				if ($row["JAWABAN"]=="5") { echo "checked='checked'"; }					
				echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN E]</label><textarea name='isiE_".$soal_kode."_".$soal_no."' id='isiE_".$soal_kode."_".$soal_no."' rows='3' cols='45' style='resize: none;'>".$row["PILE"]."</textarea><br>";
				echo "</td>";
				echo "</tr>";
        
				echo "<tr>";								
				echo "<td>";
				//C===================
				echo "&nbsp;&nbsp;&nbsp;<input required type='radio' value='3' name='pil_".$soal_kode."_".$soal_no."'";
				if ($row["JAWABAN"]=="3") { echo "checked='checked'"; }					
				echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN C]</label><textarea name='isiC_".$soal_kode."_".$soal_no."' id='isiC_".$soal_kode."_".$soal_no."' rows='3' cols='45' style='resize: none;'>".$row["PILC"]."</textarea><br>";
				echo "</td>";
				echo "&nbsp;";
				echo "<td>";
				echo "</td>";
				echo "</tr>";											
				echo "</table>";
				
				//MENAMPILKAN PEMBAHASAN============											
				echo '[PEMBAHASAN]<br>';
				echo '<textarea id="pembahasan_'.$soal_kode.'_'.$soal_no.'" name="pembahasan_'.$soal_kode.'_'.$soal_no.'" rows="3" cols="45" style="resize: none;">'.$row["PEMBAHASAN"].'</textarea><br>';
				
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
	if (isset($_REQUEST["sukses"])) {
    if ($_REQUEST["sukses"] == "0") {
      echo "<script>alert('Penambahan soal baru telah berhasil!'); </script>";      
    } else {
      echo "<script>alert('Perubahan soal nomor ".$_REQUEST["sukses"]." telah berhasil!'); </script>";
    }
	}
?>
</body>
</html>