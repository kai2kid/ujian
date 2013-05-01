<?php
class ujian {
  private $link;
  public $kodeUjian;
  public $user;
  public $ujian;
  public $nilai;
  public $data;
  public function __construct($user,$kodeUjian=0,$link) {
    $this->kodeUjian = $kodeUjian;
    $this->user = $user;
    $this->link = $link;
    if ($kodeUjian > 0) {
      $this->refreshUjian();
      $this->refreshNilai();
    }
  }
  public function preparation() {
    $this->insertNilai($this->randomSoal());    
    $this->refreshNilai();
  }
  public function refreshUjian() {
    $query = "
      SELECT *
      FROM ujian
      WHERE kode_ujian = '".$this->kodeUjian."'
      ";
    $this->ujian = mysql_fetch_assoc(mysql_query($query));
    $query = "
      SELECT *
      FROM dsoal
      WHERE kode_soal = '".$this->ujian['kode_soal'] ."'
      ";
    $sql = mysql_query($query);
    $this->data['totalSoal'] = mysql_num_rows($sql);
    $this->data['maxHal'] = ceil($this->data['totalSoal'] / $this->ujian['jum_perhalaman']);
  }
  public function refreshNilai() {
    $query = "
      SELECT *
      FROM nilai n
      WHERE username = '".$this->user."'
      AND kode_ujian = '".$this->kodeUjian."'
      ";
    $this->nilai = mysql_fetch_assoc(mysql_query($query));
  }
  public function getNow() {
    $query = "
      SELECT now() as now
      FROM ujian u
      limit 0,1
      ";
    $tmp = mysql_fetch_assoc(mysql_query($query));
    $this->data['timeNow'] = $tmp['now'];
    return $tmp['now'];
  }
  public function randomSoal() {
    $query = "
      SELECT no_urut, kode_group
      FROM dsoal 
      WHERE kode_soal = '".$this->ujian['kode_soal']."';
    ";
    $sql = mysql_query($query);
//    $num = mysql_num_rows($sql);
    $soal = array();
    $soal2 = array();
    while ($row = mysql_fetch_assoc($sql)) {
      $soal[] = $row['no_urut'];
      $soal2[$row['no_urut']] = $row['kode_group'];
    }
    $rsoal = array();
    foreach ($soal as $index => $no_urut) {
      $empty = false;
      while (!$empty) {
        $r = rand(0,count($soal)-1);
        if (!isset($rsoal[$r]) || $rsoal[$r] == "") { $empty = true; }
      }
      $rsoal[$r] = $no_urut;
    }
    $rsoal2 = "";
    for ($i=0 ; $i<= count($rsoal)-1 ; $i++) {
      $rsoal2[$i] = $rsoal[$i];
    }
    //random per bacaan
    $query = "SELECT * FROM bacaan WHERE kode_soal = '".$this->ujian['kode_soal']."';";
    $sql = mysql_query($query);
    if (mysql_num_rows($sql) > 0) { // cek adanya bacaan dalam soal
      $tmp=array();
      foreach($rsoal2 as $soal) {
        if (!in_array($soal,$tmp)) {
          $query = "SELECT start_num, end_num FROM bacaan WHERE kode_soal = '".$this->ujian['kode_soal']."' AND start_num <= $soal AND end_num >= $soal;";
          $sql = mysql_query($query);
          if (mysql_num_rows($sql) > 0) {          
            $row = mysql_fetch_assoc($sql);
            foreach($rsoal2 as $tsoal) {
              if ($tsoal >= $row['start_num'] && $tsoal <= $row['end_num']) $tmp[] = $tsoal;
            }
          } else {
            $tmp[] = $soal;
          }       
        }
      }
      $rsoal2 = $tmp;
    }
    
    //random per group
    if ($this->ujian['random_group'] > 0) {
      //get group distinct
      $query = "
        SELECT DISTINCT kode_group
        FROM dsoal 
        WHERE kode_soal = '".$this->ujian['kode_soal']."'
        ORDER BY kode_group ASC;
      ";
      $sql = mysql_query($query);
  //    $num = mysql_num_rows($sql);
      $tmp=array();
      while ($row = mysql_fetch_assoc($sql)) {
        foreach($rsoal2 as $soal) {
          if ($soal2[$soal] == $row['kode_group']) $tmp[] = $soal;
        }
      }
      $rsoal2 = $tmp;
    }
    $ret = implode("@",$rsoal2);
    return $ret;

  }
  public function countJawaban() {
    $ret = 0;
    if ($this->nilai['urutan_jawaban'] != "") {
      $tmp = explode("@",$this->nilai['urutan_jawaban']);
      $ret = count($tmp);
    }
    return $ret;
  }
  public function calculateEndDuration() {
    $durasi = 60 * $this->ujian['durasi_ujian'];
    $awal = strtotime($this->nilai['waktu_mulai']);
    $akhir = strtotime($this->ujian['waktu_akhir']);
    $last = $awal + $durasi;
    if ($last > $akhir) $last = $akhir;
    $this->data['durasiAkhir'] = $last;
    return $last;
  }
  public function getDurasi() {
    $this->getNow();
    $durasi = 60 * $this->ujian['durasi_ujian'];
    $wkt_skrg = strtotime($this->data['timeNow']);
    $wkt_akhir = strtotime($this->ujian['waktu_akhir']);
    $selisih = $wkt_akhir - $wkt_skrg;
    if ($selisih < $durasi) $durasi = $selisih;
    return $durasi;
  }
  public function getHalaman() {
    $hal = 1;
    if ($this->nilai['urutan_jawaban'] != "") {
      $jwb = explode("@",$this->nilai['urutan_jawaban']);  
      $hal = 1 + floor(count($jwb)/$this->ujian['jum_perhalaman']);    
      for ($i=0 ; $i<($hal-1)*$this->ujian['jum_perhalaman'] ; $i++) {
        $tmp[] = $jwb[$i];
      }
      $jawaban = implode("@",$tmp);
      $this->updateJawabanUjian($jawaban);
    }
    return $hal;
  }
  public function insertNilai($soal = "") {
    $query = "
      SELECT *
      FROM nilai
      WHERE username = '".$this->user."'
      AND kode_ujian = '".$this->kodeUjian."'
    ";
    $sql = mysql_query($query,$this->link);
    if (mysql_num_rows($sql) <= 0) {
      $query = "
        INSERT INTO nilai
        (`kode_ujian`,`username`,`urutan_soal`,`urutan_jawaban`,`jum_benar`,`nilai`,`waktu_mulai`)
        VALUES ('".$this->kodeUjian."','".$this->user."','$soal','','0','-1',now());
      ";    
      mysql_query($query,$this->link);
      unset($_SESSION['halaman']);
    }    
  }
  public function updateJawabanUjian($jawaban) {
    $query = "
      UPDATE nilai
      SET urutan_jawaban = '$jawaban'
      WHERE username = '".$this->user."'
      AND kode_ujian = '".$this->kodeUjian."'
    ";
    mysql_query($query);
  }
  public function updateSoalUjian($soal) {
    $query = "
      UPDATE nilai
      SET urutan_soal = '$soal'
      WHERE username = '".$this->user."'
      AND kode_ujian = '".$this->kodeUjian."'
    ";
    mysql_query($query);
  }
  public function updateDurasiUjian($sisa) {
    $query = "
      UPDATE nilai
      SET waktu_sisa = '$sisa'
      WHERE username = '".$this->user."'
      AND kode_ujian = '".$this->kodeUjian."'
    ";
    mysql_query($query);
  }
  public function updateNilaiUjian($nilai,$benar) {
    $query = "
      UPDATE nilai
      SET nilai = '$nilai'
      , jum_benar = '$benar'
      WHERE username = '".$this->user."'
      AND kode_ujian = '".$this->kodeUjian."'
    ";
    mysql_query($query);
  }
  public function getHal($sisa) {
    $this->refreshNilai();
    $tmp = explode("@",$this->nilai['urutan_jawaban']);
    $terjawab = count($tmp);
    $hal = floor($terjawab / $this->ujian['jum_perhalaman'])+1;
    return $hal;
  }
  public function getSoal($hal) {
    $this->refreshNilai();
    $tmp = explode("@",$this->nilai['urutan_soal']);
    $start = 1 + (($hal-1) * $this->ujian['jum_perhalaman']);
    for ($i=$start ; $i<=($start+$this->ujian['jum_perhalaman'])-1 ; $i++) {
      $ret[] = $tmp[$i-1];
    }
    return $ret;
  }
  public function getBacaan($noUrut) {
    $ret = "";
    $query = "SELECT isi_bacaan FROM bacaan WHERE kode_soal = '".$this->ujian['kode_soal']."' AND start_num <= $noUrut AND end_num >= $noUrut;";
    $sql = mysql_query($query);
    if (mysql_num_rows($sql) > 0) {
      $row = mysql_fetch_assoc($sql);
      $ret = $row['isi_bacaan'];
    }
    return $ret;
  }
  
  public function drawSoal($noUrut,$noSoal) {
    $jawab = "";
    if ($this->nilai['urutan_jawaban'] != "") {
      $jawab = explode("@",$this->nilai['urutan_jawaban']);
    }
    $query = "
      SELECT d.*, g.nama_group
      FROM dsoal d, group_soal g
      WHERE kode_soal = '".$this->ujian['kode_soal']."'
      AND no_urut = '$noUrut'
      AND d.kode_group = g.kode_group
    ";
    $sql = mysql_query($query);
    $row = mysql_fetch_assoc($sql);
    $latihan = false;
    if ($this->ujian['jenis'] == 1 && $row['pembahasan'] != "") {
      $latihan = true;
    }
    $s = "";
    $s .= "<tr>";
      $s .= "<td>";
        $s .= "<div name='tempatSoalJawab'>";
/*/
          Cetak Grup Soal
          if ($this->data['grupSoal'] != $row['kode_group'] && $this->ujian['random_group'] > 0) {
            $s .= "<b>[<u>".$row['nama_group']."</u>]</b>";
            $s .= "<br>";
            $this->data['grupSoal'] = $row['kode_group'];
          }
/*/
          $bacaan = $this->getBacaan($noUrut);
          if ($bacaan != "" && $this->data['bacaan'] != $bacaan) {
            $s .= $bacaan;
            $s .= "<br>";
            $this->data['bacaan'] = $bacaan;
          }
//          Gambar soal
//          $path = "./imgSoal/".$this->ujian['kode_soal']."/".$noUrut.".jpg";
//          if (file_exists($path)) {
//            $img = "<br><img src='$path' align=top width=150px><br>";
//          }
          $img = "";
          $s .= "<b>".$noSoal.". $img".$row['soal']."</b>";
          $s .= "<br>";
          
          $pil[1]['field'] = "pilA";
          $pil[1]['value'] = "1";
          $pil[2]['field'] = "pilB";
          $pil[2]['value'] = "2";
          $pil[3]['field'] = "pilC";
          $pil[3]['value'] = "3";
          $pil[4]['field'] = "pilD";
          $pil[4]['value'] = "4";
          $pil[5]['field'] = "pilE";
          $pil[5]['value'] = "5";
          $random = array();
          for ($i = 1 ; $i<=5 ; $i++) {
            $done = false;
            while (!$done) {
              $rnd = rand(1,5);
              if (!in_array($rnd,$random)) {
                $random[] = $rnd;
                $done = true;
              }
            }
          }
          $opt = array();
          foreach ($random as $r) {
            $opt[] = $pil[$r];
          }
          $s .= "<div id='option_$noSoal'>";
          foreach ($opt as $option) {
            $data = $row[$option['field']];
            $oc = ""; $id = "";
            if ($data != "") {
              if ($latihan) {
                $oc = "onclick='evaluasi(this,$noSoal);'";
                if ($row['jawaban'] == $option['value']) {
                   $id = "id='jawaban_$noSoal'";
                }
              }
              $s .= "<div $id>";
              $sel = "";
              if ($jawab[$noSoal-1] == $option['value']) { $sel = "checked"; }
              $s .= "<input type='radio' name='jwb_$noSoal' value='".$option['value']."' $oc $sel /> &nbsp $data<br>";
              $s .= "</div>";              
            }
          }
          $s .= "</div>";              
        $s .= "</div>";
        if ($latihan) {
          $s .= "<br>";
          $s .= "<div id='pembahasan_$noSoal' style='display:none;' class='pembahasan_soal'>";
            $s .= "<b><u>Pembahasan:</u></b>";
            $s .= "<br>";
            $s .= $row['pembahasan'];
          $s .= "</div>";          
        }
      $s .= "</td>";
    $s .= "</tr>";
    $ret = $s;
    return $ret;
  }
  public function drawHalaman($hal) {
    $soal = $this->getSoal($hal);
    $end = false;
    $this->data['grupSoal'] = "";
    $prev = false;
    if ($this->ujian['durasi_per_soal'] == '0') { $prev = true; }
    $s = "";
    $s .= "<form name=form_ujian id=form_ujian>";
    $s .= "<input type=hidden name=hal value='$hal' />";
    $s .= "<table cellpadding=5px cellspacing=5px style='margin:auto;'>";
    $i= 1 + (($hal-1)*$this->ujian['jum_perhalaman']);
    $ctr = 0;
    foreach($soal as $noUrut) {
      if ($i <= $this->data['totalSoal']) {
        $s .= $this->drawSoal($noUrut,$i);        
        $ctr++;
      }
      if ($i >= $this->data['totalSoal']) {
        $end = true;
      }
      $i++;
    }
    
      $s .= "<tr>";
        $s .= "<td align=center>";
          if ($prev && $hal > 1) {
            $s .= "<input id='b_submitHalaman' type=button value='Prev' onclick=\"submitHal2('".($hal)."');\"> &nbsp; &nbsp; &nbsp; ";
          }
          if ($end) {
            $s .= "<input id='b_submitHalaman' type=button value='Selesai' onclick=\"submitHal('$hal');\">";
          } else {
            $s .= "<input id='b_submitHalaman' type=button value='Next' onclick=\"submitHal('$hal');\">";
          }
        $s .= "</td>";
      $s .= "</tr>";
    $s .= "</table>";
    $s .= "</form>";
    $dur_hal = ($ctr * 60 * $this->ujian['durasi_per_soal']);
    if (isset($_SESSION['halaman']) && $_SESSION['halaman'] > 0) {      
      $now = time();
      $now -= $_SESSION['halaman'];
      $dur_hal -= $now;
    } else {
      $_SESSION['halaman'] = time();
    }
    
    if (!$prev) {
      $s .= "<script>";
        $s .= "startTimerHalaman($dur_hal);";
      $s .= "</script>";
    }
    
    $ret = $s;
    return $ret;
  }
  public function hitungNilai() {
    $this->refreshNilai();
    if ($this->nilai['nilai'] < 0) {
      $soal = explode("@",$this->nilai['urutan_soal']);
      $jawab = explode("@",$this->nilai['urutan_jawaban']);
      $query = "
        SELECT no_urut, jawaban
        FROM dsoal
        WHERE kode_soal = '".$this->ujian['kode_soal']."'
      ";
      $sql = mysql_query($query);
      while ($row = mysql_fetch_assoc($sql)) {
        $answer[$row['no_urut']] = $row['jawaban'];
      }
      $benar = 0;
      $salah = 0;
      foreach ($soal as $key=>$value) {
        if ($jawab[$key] == $answer[$value]) $benar++;
        else if ($jawab[$key] != "") $salah++;
      }
      $rumus = $this->checkRumus();
      $jumlahSoal = $this->data['totalSoal'];
      $calc = str_replace('B',$benar,$rumus);
      $calc = str_replace('J',$jumlahSoal,$calc);
      $calc = str_replace('S',$salah,$calc);
      eval("\$nilai = floor(" . $calc.");");
      if ($nilai < 0) $nilai = 0;
      $this->data['nilai'] = $nilai;
      $this->updateNilaiUjian($nilai,$benar);
      return $nilai;
        
    }
  }
  public function checkRumus() {
    $res = "B/J*100";
    $rumus = $this->ujian['rumus_nilai'];
    $valid = true;
    if ($rumus != "") {
      try {
        $calc = str_replace('B',1,$rumus);
        $calc = str_replace('J',1,$calc);
        $calc = str_replace('S',1,$calc);
        $tmp = "\$nilai = " . $calc . ";";
        eval($tmp);
        if (is_numeric($nilai)) $res = $rumus;
      } 
      catch (Exception $e) {
        $valid = false;
      }      
    }
    if (!$valid) $res = $rumus;
    return $res;
  }
  public function drawResult() {
    if ($this->ujian['jenis'] == 1) $lat = "latihan";
    $s = "";
    $s .= "<br><br>";
    $s .= "<table style='margin:auto' cellpadding=5 cellspacing=2>";
      $s .= "<tr>";
        $s .= "<td align=center>";
          $s .= "Selamat! Anda telah selesai mengerjakan $lat ujian!<br>";
          $s .= "Nilai anda adalah: <b><u>" . $this->data['nilai'] ."</u></b>";
        $s .= "</td>";
      $s .= "</tr>";
    $s .= "</table>";
    $s .= "<br><br>";
    $ret = $s;
    return $ret;
  }
  public function appendJawaban($jawaban) {
    if ($jawaban != "") {
      $tmp = "";
      if ($this->nilai['urutan_jawaban'] != "")
        $tmp = explode("@",$this->nilai['urutan_jawaban']);
      foreach ($jawaban as $value) {
        if ($value != "") {
          $tmp[] = $value;
        } else {
          $tmp[] = " ";
        }
      }
      $tmp = implode("@",$tmp);
      $this->updateJawabanUjian($tmp);      
    }
  }
  public function drawAllNilai() {
    $query = "
      SELECT n.kode_ujian, u.nama_ujian, h.nama_soal, u.pengajar, DATE(u.waktu_mulai) as waktu_mulai, u.waktu_akhir, n.jum_benar, n.nilai, u.jenis,
        (SELECT count(no_urut) FROM dsoal WHERE kode_soal = h.kode_soal) AS jumlah_soal
      FROM nilai n, hsoal h, ujian u
      WHERE n.username = '".$this->user."'
      AND u.kode_ujian = n.kode_ujian
      AND u.kode_soal = h.kode_soal      
      AND n.nilai >= 0
      ";
    $sql = mysql_query($query);
    $s .= "<br><br>";
    if (mysql_num_rows($sql) > 0) {
      $s .= "<table border=1 cellpadding=5 cellspacing=2 style='border: 1px solid #666666; border-collapse:collapse;font-size:9pt; margin-left: auto; margin-right: auto;'>";
        $s .= "<tbody>";
          $s .= "<tr class='judulTable'>";
            $s .= "<th>Kode Ujian</th>";
            $s .= "<th>Nama Ujian</th>";
            $s .= "<th>Nama Soal</th>";
            $s .= "<th>Nama Guru</th>";
            $s .= "<th>Tanggal Ujian</th>";
            $s .= "<th>Soal</th>";
            $s .= "<th>Benar</th>";
            $s .= "<th>Nilai</th>";
            $s .= "<th>Jenis</th>";
          $s .= "</tr>";
          while ($row = mysql_fetch_assoc($sql)) {            
            extract($row);
            if ($jenis == 1) $jenis = "Latihan"; else $jenis = "Ujian"; 
            $s .= "<tr>";
              $s .= "<td>$kode_ujian</td>";
              $s .= "<td>$nama_ujian</td>";
              $s .= "<td>$nama_soal</td>";
              $s .= "<td>$pengajar</td>";
              $s .= "<td>$waktu_mulai</td>";
              $s .= "<td align=right>$jumlah_soal</td>";
              $s .= "<td align=right>$jum_benar</td>";
              $s .= "<td align=right>$nilai</td>";
              $s .= "<td>$jenis</td>";
            $s .= "</tr>";
          }
        $s .= "</tbody>";
      $s .= "</table>";      
    } else {
      $s .= "<br><br>";
      $s .= "Anda belum mengikuti ujian.";
      $s .= "<br><br>";
    }
    $s .= "<br><br>";
    return $s;
  }
  public function drawAvailableUjian($latihan = 0) {
    $query="
      SELECT u.kode_ujian, u.nama_ujian, u.kode_soal, u.waktu_mulai, u.waktu_akhir, u.pengajar, h.nama_soal, u.durasi_ujian,
        (SELECT count(no_urut) FROM dsoal WHERE kode_soal = h.kode_soal) AS jumlah_soal
      FROM ujian u, hsoal h
      WHERE now() >= u.waktu_mulai 
      AND jenis = $latihan
      AND now() < u.waktu_akhir
      AND u.kode_soal = h.kode_soal
      AND u.kode_ujian IN (
        SELECT kode_ujian 
        FROM peserta_ujian 
        WHERE username='".$this->user."'
      )
      AND u.kode_ujian NOT IN (
        SELECT kode_ujian 
        FROM nilai 
        WHERE username='".$this->user."'
        AND nilai >= 0
      )
      ORDER BY u.waktu_mulai;
      ";
    $sql=mysql_query($query);
    $s .= "<style>";
      $s .= "tr.selectable:hover {";
        $s .= "cursor: pointer;";
        $s .= "background-color: #EEEEEE;";
      $s .= "}";
    $s .= "</style>";
    $s .= "<br><br>";
    if (mysql_num_rows($sql) > 0) {
      $s .= "<table border=1 cellpadding=5 cellspacing=2 style='border: 1px solid #666666; border-collapse:collapse; font-size:9pt;'>";
        $s .= "<tbody>";
          $s .= "<tr class='judulTable'>";
            $s .= "<th>Kode Ujian</th>";
            $s .= "<th>Nama Ujian</th>";
            $s .= "<th>Nama Soal</th>";
            $s .= "<th>Nama Guru</th>";
            $s .= "<th>Mulai</th>";
            $s .= "<th>Selesai</th>";
            $s .= "<th>Soal</th>";
            
//            $s .= "<th>Sisa Waktu</th>";
          $s .= "</tr>";
          $i = 1;
          while ($row = mysql_fetch_assoc($sql)) {
            extract($row);
//            $durasi = $this->getSisaWaktu($kode_ujian);
//            $sisa = $durasi['custom'];
            $s .= "<tr onclick=\"document.location='OptionUjianSiswa.php?kdsoal=".$kode_soal."&kdujian=".$kode_ujian."'\" class=selectable>";
              $s .= "<td>$kode_ujian</td>";
              $s .= "<td>$nama_ujian</td>";
              $s .= "<td>$nama_soal</td>";
              $s .= "<td>$pengajar</td>";
              $s .= "<td align=center>$waktu_mulai</td>";
              $s .= "<td align=center>$waktu_akhir</td>";
              $s .= "<td align=right>$jumlah_soal</td>";
//              $s .= "<td align=center>$sisa</td>";
            $s .= "</tr>";
            $i++;
          }
        $s .= "</tbody>";
      $s .= "</table>";      
    } else {
      $s .= '<div style="text-align:center">Anda tidak terdaftar sebagai peserta ujian apapun pada waktu ini...</div>';
    }                
   $s .= "<br><br>";
   return $s;
  }
  public function getSisaWaktu() {
    $last = $this->calculateEndDuration();
    $now = $this->getNow();
    $sisa = $last - strtotime($now);
    return $sisa;
  }
  public function formatSecond($seconds) {
    $dur['full'] = $seconds;
    $dur['jam'] = floor($seconds / (60*60));
    $dur['menit'] = floor(($seconds-($dur['jam']*60*60)) / 60);
    $dur['detik'] = $seconds % 60;
    $dur['custom'] = str_pad($dur['jam'],2,"0",STR_PAD_LEFT) . ":" . str_pad($dur['menit'],2,"0",STR_PAD_LEFT) . ":" . str_pad($dur['detik'],2,"0",STR_PAD_LEFT);
    return $dur;
  }
}

?>