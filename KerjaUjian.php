<?php 
  include_once("ConnectionString.php"); 
  include_once("class_ujian.php");
  session_start();
  if ($_SESSION['JENIS']!='S'){
    header("location:index.php");
  }

  extract($_REQUEST);
  $kdujian = $_SESSION['kdujian'];
  $user = $_SESSION['USER'];
  $ujian = new ujian($user,$kdujian,$dbConn);
  $s = "";

  switch ($act) {
    case "open" : {
      $s .= $ujian->drawHalaman($hal);
      break;      
    }
    case "submit" : {
      if ($hal > 0) {
        if ($ujian->nilai['urutan_jawaban'] != "") {
          $jawab = explode("@",$ujian->nilai['urutan_jawaban']);          
        } else {
          $jawab = array();
        }
        for ($i = 1 ; $i<= $ujian->ujian['jum_perhalaman'] ; $i++) {
          $noSoal = $i + ($hal - 1) * $ujian->ujian['jum_perhalaman'];
          if ($noSoal <= $ujian->data['totalSoal']) {
            $jawaban = $_REQUEST['jwb_'.$noSoal];
            if ($jawaban == "") $jawaban = " ";
            $jawab[$noSoal-1] = $jawaban;
          }
        }
        unset($_SESSION['halaman']);
        $jawab = implode("@",$jawab);
        $ujian->updateJawabanUjian($jawab);
//        $ujian->appendJawaban($jawab);
      }
      $hal++;
      if ($hal > $ujian->data['maxHal']) {
        $s .= "<script>finishKerja();</script>";
      } else {        
        $s .= $ujian->drawHalaman($hal);
      }
      break;
    }
    case "submit2" : {
      if ($hal > 0) {
        if ($ujian->nilai['urutan_jawaban'] != "") {
          $jawab = explode("@",$ujian->nilai['urutan_jawaban']);          
        } else {
          $jawab = array();
        }
        for ($i = 1 ; $i<= $ujian->ujian['jum_perhalaman'] ; $i++) {
          $noSoal = $i + ($hal - 1) * $ujian->ujian['jum_perhalaman'];
          if ($noSoal <= $ujian->data['totalSoal']) {
            $jawaban = $_REQUEST['jwb_'.$noSoal];
            if ($jawaban == "") $jawaban = " ";
            $jawab[$noSoal-1] = $jawaban;
          }
        }
        unset($_SESSION['halaman']);
        $jawab = implode("@",$jawab);
        $ujian->updateJawabanUjian($jawab);
      }
      $hal--;
      if ($hal > 0) {
        $s .= $ujian->drawHalaman($hal);
      }
      break;
    }
    case "finish" : {
      $ujian->hitungNilai();
      $s .= "<script>alert('Selamat! Anda sudah selesai mengerjakan soal-soal!');</script>";
      $s .= $ujian->drawResult();      
    }
  }
  echo $s;
 ?>
