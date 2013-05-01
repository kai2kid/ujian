<?php include("ConnectionString.php"); ?>
<?php include_once("class_ujian.php"); ?>
<?php
	session_start();
	//$_SESSION['timeout'] = 0;
  if ($_SESSION['JENIS']!='S'){
    header("location:index.php");
	}
  $kd_soal = $_GET['kdsoal'];
  $kd_ujian = $_GET['kdujian'];
  $user = $_SESSION['USER'];
  $ujian = new ujian($user,$kd_ujian,$dbConn);  
  if ($ujian->nilai['nilai'] >= 0 && $ujian->nilai['nilai'] != "") {
    header("location:indexSiswa.php");    
  }
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("_head.php"); ?>
<body>
<center>
<div id="header"></div>
		<div id="main" class="post">
				<?php
          $s = "";
				  $_SESSION['kdujian']=$_GET['kdujian'];
					$query="
            SELECT u.nama_ujian, h.nama_soal, u.random_soal, u.random_group, u.durasi_ujian, u.durasi_per_soal, u.jum_perhalaman, u.jenis
            FROM hsoal h, ujian u 
            WHERE u.kode_soal= '$kd_soal'
            AND u.kode_ujian = '$kd_ujian'
            AND h.kode_soal = u.kode_soal
          ";
					$result=mysql_query($query,$dbConn);
          $check_false = "<input type=checkbox disabled>";
          $check_true  = "<input type=checkbox checked disabled>";
					$row=mysql_fetch_assoc($result);
          if ($row['jenis'] == 1) $lat = "Latihan ";
          ?>
      <h2 class="title" ><span>
        <?php echo $lat . "Ujian untuk " . $_SESSION['NAMA']; ?>
      </span></h2>
      <form id="formOpUjian" name="formOpUjian" method="post" action="MulaiUjian.php">
        <center>
          
          <?php
          $s .= "<input type=hidden name=tkodes value='".$kd_soal."'>";
          $s .= "<table>";
//            $tmp = $ujian->getSisaWaktu($_GET['kdujian']);
//            $sisa = $tmp['custom'];
					  $s .= "<tr>";
							$s .= "<td class='log' width=50% style='text-align:right'><b>Kode Soal :</b></td>";
//              $s .= "<td class='log'><input id='tkodes' readonly='readonly' type='text' name='tkodes'   value='". $_GET['kdsoal'] ."' style='padding-left:10px;'/></td>";
							$s .= "<td class='log' width=50%> ". $kd_soal ."</td>";
            $s .= "</tr>";
            $s .= "<tr>";
              $s .= "<td class='log' style='text-align:right'><b>Nama Ujian :</b></td>";
              $s .= "<td class='log'> ". $row['nama_ujian'] ."</td>";
            $s .= "</tr>";
            $s .= "<tr>";
							$s .= "<td class='log' style='text-align:right'><b>Nama Soal :</b></td>";
              $s .= "<td class='log'> ". $row['nama_soal'] ."</td>";
            $s .= "</tr>";
            $s .= "<tr>";
              $s .= "<td class='log' style='text-align:right'><b>Random Soal :</b></td>";
              $s .= "<td class='log'> ";
                if($row['random_soal']==0){$s .= $check_false;}else{$s .= $check_true;}
              $s .= "</td>";
            $s .= "</tr>";
            $s .= "<tr>";
              $s .= "<td class='log' style='text-align:right'><b>Random Group :</b></td>";
							$s .= "<td class='log'> ";
							  if($row['random_group']==0){$s .= $check_false;}else{$s .= $check_true;}
              $s .= "</td>";
            $s .= "</tr>";
            $s .= "<tr>";
              $s .= "<td class='log' style='text-align:right'><b>Lama Ujian :</b></td>";
              $s .= "<td class='log'> ". $row['durasi_ujian'] ." Menit</td>";
            $s .= "</tr>";
            /*/
            $s .= "<tr>";
              $s .= "<td class='log' style='text-align:right'><b>Sisa Waktu :</b></td>";
              $s .= "<td class='log'> ". $sisa ."</td>";
            $s .= "</tr>";
            /*/
            $s .= "<tr>";
              $s .= "<td class='log' style='text-align:right'><b>Waktu per Soal :</b></td>";
              $s .= "<td class='log'> ". $row['durasi_per_soal'] ." Menit</td>";
            $s .= "</tr>";
            $s .= "<tr>";
              $s .= "<td class='log' style='text-align:right'><b>Jumlah Soal Per Halaman :</b></td>";
							$s .= "<td class='log'> ".$row['jum_perhalaman']."</td>";
            $s .= "</tr>";
          $s .= "<tr>";
            $s .= "<td colspan=2 align=center>";
              $s .= "<br>";
              $s .= "<input id='btnOK' type='submit' name='btnOK' value='Mulai Ujian' />";
            $s .= "</td>";
          $s .= "</tr>";
          $s .= "</table>";
          echo $s;
				?>
				
				</center>
		    </form>
			<div class="meta">
				<br/>
				<center>
<?php
  $a = "<a href='UjianSiswa.php'> Kembali ke halaman ujian </a>";
  if ($row['jenis'] == 1) {
    $a = "<a href='LatihanSiswa.php'> Kembali ke halaman latihan ujian </a>";
  }
  echo $a;
?>
        </center>
			</div>
		</div>
<?php include("_footer.php"); ?>
<center>
</body>
</html>