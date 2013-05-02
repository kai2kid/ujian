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
   
  if ($_REQUEST['modeProses']=='INSERT'){ 
    $query="INSERT INTO HSOAL (NAMA_SOAL, TGL_INSERT, INSERT_BY, KODE_MAPEL) values ('".$_REQUEST["edNama"]."', sysdate(), '".$_SESSION['USER']."', ".$_REQUEST["cbMapel"].")";    
    mysql_query($query,$dbConn);

    $query="SELECT KODE_SOAL, NAMA_SOAL FROM HSOAL ORDER BY KODE_SOAL DESC";  
    $result = mysql_query($query,$dbConn);
    
    $row = mysql_fetch_assoc($result);    
    $_SESSION["kode_soal"] = $row["KODE_SOAL"];  
    $_SESSION["nama_soal"] = $row["NAMA_SOAL"];
    $_SESSION["group_soal"] = $_REQUEST["cbGroup"];
    $_SESSION["kode_mapel"] = $_REQUEST["cbMapel"];
    $kode = $row["KODE_SOAL"];
    
    header("location:EditSoal.php?kode_soal=".$row["KODE_SOAL"]);
  } else 
	if ($_REQUEST['modeProses']=='SOAL'){
    $soal_kode = $_REQUEST["kode_soal"];
    $soal_no = $_REQUEST["cbNo"];
    $tmp = $soal_kode."_".$soal_no;
		$pilA = $_REQUEST["isiA_".$tmp];
		$pilB = $_REQUEST["isiB_".$tmp];
		$pilC = $_REQUEST["isiC_".$tmp];
		$pilD = $_REQUEST["isiD_".$tmp];
		$pilE = $_REQUEST["isiE_".$tmp];
    $soal = $_REQUEST["soal_".$tmp];
    $grup = $_REQUEST["cbGroup"];
		$jawaban = $_REQUEST["pil_".$tmp];
		$pembahasan = $_REQUEST["pembahasan_".$tmp];
		
		if (substr($pilA,0,3)=="<p>") { $pilA = trim(substr($pilA,3,sizeof($pilA)-7)); }		
		if (substr($pilB,0,3)=="<p>") { $pilB = trim(substr($pilB,3,sizeof($pilB)-7)); }		
		if (substr($pilC,0,3)=="<p>") { $pilC = trim(substr($pilC,3,sizeof($pilC)-7)); }		
		if (substr($pilD,0,3)=="<p>") { $pilD = trim(substr($pilD,3,sizeof($pilD)-7)); }		
		if (substr($pilE,0,3)=="<p>") { $pilE = trim(substr($pilE,3,sizeof($pilE)-7)); }		
    if (substr($soal,0,3)=="<p>") { $soal = trim(substr($soal,3,sizeof($soal)-7)); }
		if (substr($pembahasan,0,3)=="<p>") { $pembahasan = trim(substr($pembahasan,3,sizeof($pembahasan)-7)); }
		
    if ($_REQUEST['cbNo'] == '0') { // Nomor baru
      $query = "
        INSERT INTO DSOAL 
        (KODE_SOAL, SOAL, PILA, PILB, PILC, PILD, PILE, JAWABAN, KODE_GROUP, PEMBAHASAN) 
        VALUES 
        ('$soal_kode', '$soal', '$pilA', '$pilB', '$pilC', '$pilD', '$pilE', $jawaban, '$grup', '$pembahasan')";
    } else { //Update nomor soal
      $query = "
        UPDATE DSOAL 
        SET SOAL='$soal',PILA='$pilA',PILB='$pilB',PILC='$pilC',PILD='$pilD',PILE='$pilE',
          JAWABAN=$jawaban,KODE_GROUP='$grup',PEMBAHASAN='$pembahasan'
        WHERE KODE_SOAL='$soal_kode' 
          AND NO_URUT='$soal_no'";
    }
    $err = 1;
		if (mysql_query($query)) $err = 0;
		
    //bacaan
    $isi_bacaan = $_REQUEST["bacaan_".$soal_kode];
    
    if (strlen(trim($isi_bacaan)) > 7) {
      $start_num = $_REQUEST["edStart"];
      $end_num = $_REQUEST["edEnd"];
      if (substr($pembahasan,0,3)=="<p>") { $pembahasan = trim(substr($pembahasan,3,sizeof($pembahasan)-7)); }
      if ($_REQUEST["kode_bacaan"] == "") {
        //save bacaan
        $query = "INSERT INTO BACAAN (KODE_SOAL, ISI_BACAAN, START_NUM, END_NUM) VALUES ('".$soal_kode."', '".$isi_bacaan."', '".$start_num."', '".$end_num."')";              
      } else {
		    //update bacaan
		    $query = "UPDATE BACAAN SET ISI_BACAAN='".$isi_bacaan."', START_NUM=".$start_num.", END_NUM=".$end_num." WHERE KODE_SOAL='".$soal_kode."' AND KODE_BACAAN='".$_REQUEST["kode_bacaan"]."'";
	    }
      mysql_query($query);
    }

		header("location:EditSoal.php?kode_soal=".$soal_kode."&sukses=".$soal_no);
	}
?>