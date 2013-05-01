<?php 
  include_once("ConnectionString.php");
  include_once("class_ujian.php");
  session_start();
  //$_SESSION['timeout'] = 0;
  if ($_SESSION['JENIS']!='S' || !isset($_POST['tkodes'])){
    header("location:index.php");
	}
  $tkodes = $_POST['tkodes'];
  $kdujian = $_SESSION['kdujian'];
  $user = $_SESSION['USER'];
  $ujian = new ujian($user,$kdujian,$dbConn);
  $ujian->preparation();  
  if ($ujian->nilai['urutan_soal'] == "") {
    $ujian->updateSoalUjian($ujian->randomSoal());
  }
  if ($ujian->nilai['nilai'] >= 0) {
    header("location:indexSiswa.php");    
  }
  
//    SELECT TIME_TO_SEC((now(),u.waktu_mulai)) as selisih,u.waktu_akhir, u.durasi_ujian
// DURASI
  $durasi = $ujian->getSisaWaktu();  
/*/
	while($row=mysql_fetch_assoc($result)){
		$random=$row[RandomSoal];
		$_SESSION['back']=$row[AllowBack];
		$sstart=$row[WaktuMulai];
		$finish=$row[WaktuSelesai];
		//durasi= durasi ujian sebenarnya - (waktu sekarang-waktu mulai ujian)
		$durasi=($row[durasi]*60)-$row[selisih];
	}
	$query= "select RandomSoal,AllowBack,durasi from hsoal where KodeSoal='".$_POST['tkodes']."'";
	$result=mysql_query($query,$dbConn);
	while($row=mysql_fetch_assoc($result)){
		$random=$row[RandomSoal];
		$_SESSION['back']=$row[AllowBack];
	}
	//kalo udah ada soal n jawaban, ga usah dirandom
	$query= "select UrutanSoal, Jawaban from useraktif where NRP='".$_SESSION['user']."' and kodeujian='".$_SESSION['kdujian'] ."'";
	$result=mysql_query($query,$dbConn);
	while($row=mysql_fetch_assoc($result)){
		if($row[UrutanSoal]!=''){ // && $row[Jawaban]!=''
			$random=3;
			$_SESSION['urutansoal']=explode("@",$row[UrutanSoal]);
			//echo('urutan soal ato jawaban udah ada');
		}

	}

/*/

/*/
	$query= "select max(no) as jml from dsoal where KodeSoal='".$_POST['tkodes']."'";
	$result=mysql_query($query,$dbConn);
	while($row=mysql_fetch_assoc($result)){
		$jmlsoal=$row[jml];
	}

/*/

/*/
	if ($random==1){
		//print('urutan soal dirandom ulang');
    $urutansoal[0]="";
    $urutansoal2="";
		$_SESSION['jawab']="";
		$jml=-1;
		$query= "select no,jawab from dsoal where kode_soal='$tkodes'";
		$result=mysql_query($query,$dbConn);
		while($row=mysql_fetch_assoc($result)){
			$jml++;
			$djawab[$jml]=$row[jawab];
		}

		for($i=0;$i<=$jml;$i++){
		  $sudah=true;
		  while($sudah){
			$no=rand(1,$jml+1);
			$sudah=in_array($no,$urutansoal);
		  }
		  $urutansoal[$i]=$no;
		  $urutansoal2.=$no."@";
		}
		$_SESSION['urutansoal']=$urutansoal;

		$query="
      UPDATE useraktif 
      SET UrutanSoal='".$urutansoal2."' 
      WHERE kode_ujian='$kdujian' 
      AND username='".$_SESSION['user']."'
      ";
		$result=mysql_query($query,$dbConn);
		//echo('urutan soal dirandom ulang');
	}
  
	if($random==0){
		//print('urutan soal urut normal');
		//print('mulaiujian:');
		$urutansoal2="";
		for($i=0;$i<$jmlsoal;$i++){
			$_SESSION['urutansoal'][$i]=$i+1;
			$urutansoal2.=($i+1)."@";

		}
		//print($urutansoal2);
		$query="
      UPDATE useraktif 
      SET UrutanSoal='" . $urutansoal2 . "' 
      WHERE kodeujian='$kdujian' 
      AND username='" . $_SESSION['USER'] . "'
      ";
		$result=mysql_query($query,$dbConn);
		//echo('urutan soal normal');
	}
/*/

//PERNAH UJIAN?
  $hal = $ujian->getHalaman();
?>
<html xmlns='http://www.w3.org/19099/xhtml'>
<?php include("_head.php"); ?>
<?php
$script .= "
<script src='script/jquery-1.7.1.js'></script>
<script>
  $(document).ready( function() {
    openHal($hal);
  });
  function submitHal(hal) {
    $('#ujian').fadeOut();
    $.post('KerjaUjian.php?'+$('#form_ujian').serialize(),{ act:'submit', hal:hal }, function(data) {
      $('#ujian').html(data);
    });
    $('#ujian').fadeIn();
  }
  function submitHal2(hal) {
    $('#ujian').fadeOut();
    $.post('KerjaUjian.php?'+$('#form_ujian').serialize(),{ act:'submit2', hal:hal }, function(data) {
      $('#ujian').html(data);
    });
    $('#ujian').fadeIn();
  }
  function openHal(hal) {
    $.post('KerjaUjian.php',{ act:'open', hal:hal }, function(data) {
      $('#ujian').html(data);
    });
  }
  function finishKerja() {
    $.post('KerjaUjian.php',{ act:'finish' }, function(data) {
      $('#ujian').html(data);
    });
    $('#bottomLink').attr('href','indexSiswa.php');
    $('#bottomLink').text('Kembali ke halaman index');
    $('#waktu_halaman').hide();
    $('#waktu_halaman').stop();
    $('#countdown').stop();
  }
  function pad (str, max) {
    return str.length < max ? pad('0' + str, max) : str;
  }
  jQuery.fn.countDown = function (settings,to) {
    settings = jQuery.extend({
      startFontSize: '12px',
      endFontSize: '12px',
      duration: 1000,
      startNumber: 10,
      endNumber: 0,
      callBack: function() { }
    }, settings);
    return this.each(function() {
    //where do we start?
    if(!to  && to!= settings.endNumber) { to = settings.startNumber; }

    //set the countdown to the starting number
    var jam = Math.floor(to/3600);
    var menit = Math.floor(to%3600/60);
    var detik = (to%60);
    jam = pad(String(jam),2);
    menit = pad(String(menit),2);
    detik = pad(String(detik),2);
    $(this).text( jam + ':' + menit + ':' + detik).css('fontSize',settings.startFontSize);
    document.getElementById('waktudetik').value=to;
    //loopage
    $(this).animate({
      'fontSize': settings.endFontSize
    },settings.duration,'',function() {
      if(to > settings.endNumber + 1) {
        $(this).css('fontSize',settings.startFontSize).text(to - 1).countDown(settings,to - 1);
      } else {
        settings.callBack(this);
      }
    });
  });
};

function startcountdown(lama){
  $('#countdown').countDown({
    startNumber: lama,
    callBack: function(me) {
      $(me).text('Waktu telah habis.').css('color','red');
      var jwb=document.getElementsByName('tempatSoalJawab');
      for (var i=0;i<jwb.length;i++){
        jwb[i].style.display='none';
      }
      //document.getElementById('tempatSoalJawab').style.display='none';
      document.getElementById('waktudetik').value=0;
      $('#ujian').html('');
      alert('Waktu telah habis...');
      finishKerja();
    }
  });
}
function startTimerHalaman(durasi){
  $('#waktuPerHalaman').stop();
  $('#waktuPerHalaman').countDown({
    startNumber: durasi,
    callBack: function(me) {
      $('#b_submitHalaman').click();
    }
  });
}
function evaluasi(o,no) {
  $('#pembahasan_'+no).slideDown();
  $(o).parent().addClass('pilihan_salah');
  $('#jawaban_'+no).removeClass().addClass('pilihan_benar');
//  $(o).parent().parent().disabled = 'disabled';
  $('input[name=\"jwb_'+no+'\"]').css('visibility','hidden');
}
</script>";
  $s .= "";
    $s .= $script;
    $s .= "<body onload=\"startcountdown('$durasi');\">";
      $s .= "<input type='hidden' id='waktudetik' name='waktudetik' value=''>";
        
      $s .= "<div id='header'></div>";
        
      $s .= "<div id='main'>";
        $s .= "<div id='welcome' class='post'>";
          $s .= "<div style='padding:0 25px 0 25px;'>";
            $s .= "<h2 class='title'>";
              if ($ujian->ujian['jenis'] == 1) {
                $s .= "Latihan ";
              }
              $s .= "Ujian untuk " . $_SESSION['NAMA'];
              $s .= "<div style='float:right;' id='countdown'></div>";
              $s .= "<div style='float:right;font-size:12px;margin-right:5px;'>Waktu Ujian: </div>";
            $s .= "</h2>";
          $s .= "</div>";
          
          $s .= "<div id='ujian'></div>";          
          $s .= "<div class='meta'>";
            $s .= "<br/>";
            $s .= "<center>";          
              if ($ujian->ujian['durasi_per_soal'] != '0') {
                $s .= "<div id='waktu_halaman'>Sisa waktu untuk halaman ini: <div id='waktuPerHalaman'></div></div>";
              }
              $s .= "<a id=bottomLink href='OptionUjianSiswa.php?kdsoal=".$tkodes."&kdujian=".$kdujian."'>";
                $s .= "Kembali ke halaman Option Ujian";
              $s .= "</a>";
            $s .= "</center>";
          $s .= "</div>";
        $s .= "</div>";//<!-- end #welcome -->
      $s .= "</div>";//end #main
  echo $s;
?>
<?php include("_footer.php"); ?>
  </body>
</html>
