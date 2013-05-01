<?php
include("ConnectionString.php"); 

$param = explode("|",$_REQUEST["kode"]);
$lama = explode("|",$_REQUEST["kode"]);

?>

<script language="javascript">oldVal = $("#cbNo").val();</script>

<?php

//MENAMPILKAN BACAAN===============
$query="SELECT ISI_BACAAN, KODE_BACAAN, START_NUM, END_NUM FROM BACAAN WHERE KODE_SOAL='".$param[1]."' AND START_NUM<=".$param[0]." AND ".$param[0]."<=END_NUM ";						
$result = mysql_query($query);		
$row = mysql_fetch_assoc($result);
$jum = mysql_num_rows($result);
if ($jum>0)
{
echo '[BACAAN] untuk soal nomor ';
echo "<input type='text' name='edStart' id='edStart' value='".$row["START_NUM"]."' size='1'>&nbsp;-&nbsp;";
echo "<input type='text' name='edEnd' id='edEnd' value='".$row["END_NUM"]."' size='1'>";
echo "<input type='hidden' name='kode_bacaan' id='kode_bacaan' value='".$row["KODE_BACAAN"]."'>";
echo '<br>';
echo '<textarea id="bacaan_'.$param[1].'" name="bacaan_'.$param[1].'" rows="3" cols="45" style="resize: none;">'.$row["ISI_BACAAN"].'</textarea><br>';
echo '<script type="text/javascript">';
echo 'editorBacaan = CKEDITOR.replace("bacaan_'.$param[1].'", {height: 150, width: 750});';				
echo 'CKFinder.setupCKEditor(editorBacaan, "ckfinder");';
echo '</script>';
}

//MENAMPILKAN SOAL=================				
$query="SELECT DSOAL.PEMBAHASAN, DSOAL.NO_URUT, DSOAL.SOAL, DSOAL.KODE_SOAL, DSOAL.PILA, DSOAL.PILB, DSOAL.PILC, DSOAL.PILD, DSOAL.PILE, DSOAL.JAWABAN, GROUP_SOAL.NAMA_GROUP FROM DSOAL, GROUP_SOAL WHERE GROUP_SOAL.KODE_GROUP=DSOAL.KODE_GROUP AND DSOAL.KODE_SOAL='".$param[1]."' AND DSOAL.NO_URUT='".$param[0]."' ";						
$result = mysql_query($query);		
$row=mysql_fetch_assoc($result);
echo '[SOAL]<br>';
echo '<textarea id="soal_'.$row["KODE_SOAL"].'_'.$row["NO_URUT"].'" name="soal_'.$row["KODE_SOAL"].'_'.$row["NO_URUT"].'" rows="3" cols="45" style="resize: none;">'.$row["SOAL"].'</textarea><br>';
echo '<script type="text/javascript">';
echo 'editorSoal = CKEDITOR.replace( "soal_'.$row["KODE_SOAL"].'_'.$row["NO_URUT"].'", {height: 120, width: 750});';				
echo 'CKFinder.setupCKEditor(editorSoal, "ckfinder");';
echo '</script>';													

			
echo "<table cellpadding='2' cellspacing='3'>";
echo "<tr>";								
echo "<td>";
//A===================
echo "&nbsp;&nbsp;&nbsp;<input type='radio' value='1' name='pil_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."'";
if ($row["JAWABAN"]=="1") {
	echo "checked='checked'";
}
echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN A]</label><textarea name='isiA_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' id='isiA_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' rows='3' cols='45' style='resize: none;'>".$row["PILA"]."</textarea><br>";
echo "<script type='text/javascript'>";
echo "isiA = CKEDITOR.replace( 'isiA_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."', {height: 75, width: 400});";
echo 'CKFinder.setupCKEditor(isiA, "ckfinder");';
echo "</script>";
echo "</td>";				
echo "<td>";
//D===================					
echo "&nbsp;&nbsp;&nbsp;<input type='radio' value='4' name='pil_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."'";					
if ($row["JAWABAN"]=="4") {
	echo "checked='checked'";
}										
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
if ($row["JAWABAN"]=="2") {
	echo "checked='checked'";
}									
echo ">&nbsp;&nbsp;<label class='namaPil'>[PILIHAN B]</label><textarea name='isiB_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' id='isiB_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."' rows='3' cols='45' style='resize: none;'>".$row["PILB"]."</textarea><br>";
echo "<script type='text/javascript'>";
echo "isiB = CKEDITOR.replace( 'isiB_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."', {height: 75, width: 400});";
echo 'CKFinder.setupCKEditor(isiB, "ckfinder");';
echo "</script>";		
echo "</td>";				
echo "<td>";
//E===================
echo "&nbsp;&nbsp;&nbsp;<input type='radio' value='5' name='pil_".$row["KODE_SOAL"]."_".$row["NO_URUT"]."'";
if ($row["JAWABAN"]=="5") {
	echo "checked='checked'";
}					
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
if ($row["JAWABAN"]=="3") {
	echo "checked='checked'";
}					
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
?>

