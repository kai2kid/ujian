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
	if ($_REQUEST['modeProses']=='INSERT2'){
		//insert header soal	
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
		
		//insert detail soal===============
			
		$AllSoal = explode("#SOAL#",$_REQUEST["soal"]);	
		for ($i=0; $i<sizeof($AllSoal); $i++)		
		{			
			$pos = strpos($AllSoal[$i],"#BACAAN#");
			$posend = strpos($AllSoal[$i],"#END-BACAAN#");
			if ($pos > 0)
			{
				$bacaan = substr($AllSoal[$i],$pos+9,$posend-$pos-9);
				$untuk = substr($bacaan,0,strpos($bacaan,"#"));
				
				$startUn = substr($untuk,0,strpos($untuk,"-"));
				$stopUn = substr($untuk,strpos($untuk,"-")+1);
				
				$bacaan = trim(substr($bacaan,strpos($bacaan,"#")+1));	
				
				//save bacaan
				$query = "INSERT INTO BACAAN (KODE_SOAL, ISI_BACAAN, START_NUM, END_NUM) VALUES ('".$kode."', '".$bacaan."', '".$startUn."', '".$stopUn."')";							
				mysql_query($query,$dbConn);
			}
			
			$pos = strpos($AllSoal[$i],"#END-SOAL#");
			if ($pos > 0)
			{
				$isiSoal = substr($AllSoal[$i],0,$pos);				
				
				$pil = substr($AllSoal[$i],$pos+10);				
				$AllPil = explode("#END-PIL#",$pil);
				
				$pilA = trim(substr($AllPil[0],strpos($AllPil[0],"#PIL#")+5));
				$pilB = trim(substr($AllPil[1],strpos($AllPil[1],"#PIL#")+5));
				$pilC = trim(substr($AllPil[2],strpos($AllPil[2],"#PIL#")+5));
				$pilD = trim(substr($AllPil[3],strpos($AllPil[3],"#PIL#")+5));
				$pilE = trim(substr($AllPil[4],strpos($AllPil[4],"#PIL#")+5));
				
				if (substr($pilA,0,3)=="<p>") {
					$pilA = trim(substr($pilA,3,sizeof($pilA)-7));
				}
				
				if (substr($pilB,0,3)=="<p>") {
					$pilB = trim(substr($pilB,3,sizeof($pilB)-7));
				}
				
				if (substr($pilC,0,3)=="<p>") {
					$pilC = trim(substr($pilC,3,sizeof($pilC)-7));
				}
				
				if (substr($pilD,0,3)=="<p>") {
					$pilD = trim(substr($pilD,3,sizeof($pilD)-7));
				}
				
				if (substr($pilE,0,3)=="<p>") {
					$pilE = trim(substr($pilE,3,sizeof($pilE)-7));
				}
				
				$pembahasan = "-";				
				if (sizeof($AllPil)>5)
				{
					$AllPil[5] = trim($AllPil[5]);					
					$pembahasan = substr($AllPil[5],strpos($AllPil[5],"#BAHASAN#")+9);	
					$pembahasan = substr($pembahasan,0,strpos($pembahasan,"#END-BAHASAN#"));
				}							
				
				$jawaban = 0;
				
				if ($pilA[0]=="*") 
				{
					$pilA = trim(substr($pilA, 1, strlen($pilA)-1));
					$jawaban = 1;
				} else if ($pilB[0]=="*") 
				{
					$pilB = trim(substr($pilB, 1, strlen($pilB)-1));
					$jawaban = 2;
				} else if ($pilC[0]=="*") 
				{
					$pilC = trim(substr($pilC, 1, strlen($pilC)-1));
					$jawaban = 3;
				} else if ($pilD[0]=="*") 
				{
					$pilD = trim(substr($pilD, 1, strlen($pilD)-1));
					$jawaban = 4;
				} else if ($pilE[0]=="*") 
				{
					$pilE = trim(substr($pilE, 1, strlen($pilE)-1));
					$jawaban = 5;
				}
				
				//save soal
				$query = "INSERT INTO DSOAL (KODE_SOAL, SOAL, PILA, PILB, PILC, PILD, PILE, JAWABAN, KODE_GROUP, PEMBAHASAN) VALUES ('".$kode."', '".$isiSoal."', '".$pilA."', '".$pilB."', '".$pilC."', '".$pilD."', '".$pilE."', ". $jawaban. ", '".$_REQUEST["cbGroup"]."', '".$pembahasan."')";															
				mysql_query($query,$dbConn);
			}								
		}		
		
		//$thisdir = getcwd(); 
		//mkdir($thisdir ."/imgSoal/".$kode , 0777);
		header("location:NewSoal.php");
		
	}
  else if ($_REQUEST['modeProses']=='TAMBAH') {
		//insert detail soal	
			$kode = $_SESSION["kode_soal"];
			$_SESSION["group_soal"] = $_SESSION["group_soal"]."|".$_REQUEST["cbGroup"];
				
			$AllSoal = explode("#SOAL#",$_REQUEST["soal"]);	
			for ($i=0; $i<sizeof($AllSoal); $i++)		
			{			
				$pos = strpos($AllSoal[$i],"#BACAAN#");
				$posend = strpos($AllSoal[$i],"#END-BACAAN#");
				if ($pos > 0)
				{
					$bacaan = substr($AllSoal[$i],$pos+9,$posend-$pos-9);
					$untuk = substr($bacaan,0,strpos($bacaan,"#"));
					
					$startUn = substr($untuk,0,strpos($untuk,"-"));
					$stopUn = substr($untuk,strpos($untuk,"-")+1);
					
					$bacaan = trim(substr($bacaan,strpos($bacaan,"#")+1));	
					
					//save bacaan
					$query = "INSERT INTO BACAAN (KODE_SOAL, ISI_BACAAN, START_NUM, END_NUM) VALUES ('".$kode."', '".$bacaan."', '".$startUn."', '".$stopUn."')";							
					mysql_query($query,$dbConn);
				}
				
				$pos = strpos($AllSoal[$i],"#END-SOAL#");
				if ($pos > 0)
				{
					$isiSoal = substr($AllSoal[$i],0,$pos);				
					
					$pil = substr($AllSoal[$i],$pos+10);				
					$AllPil = explode("#END-PIL#",$pil);
					
					$pilA = trim(substr($AllPil[0],strpos($AllPil[0],"#PIL#")+5));
					$pilB = trim(substr($AllPil[1],strpos($AllPil[1],"#PIL#")+5));
					$pilC = trim(substr($AllPil[2],strpos($AllPil[2],"#PIL#")+5));
					$pilD = trim(substr($AllPil[3],strpos($AllPil[3],"#PIL#")+5));
					$pilE = trim(substr($AllPil[4],strpos($AllPil[4],"#PIL#")+5));
					
					if (substr($pilA,0,3)=="<p>") {
						$pilA = trim(substr($pilA,3,sizeof($pilA)-7));
					}
					
					if (substr($pilB,0,3)=="<p>") {
						$pilB = trim(substr($pilB,3,sizeof($pilB)-7));
					}
					
					if (substr($pilC,0,3)=="<p>") {
						$pilC = trim(substr($pilC,3,sizeof($pilC)-7));
					}
					
					if (substr($pilD,0,3)=="<p>") {
						$pilD = trim(substr($pilD,3,sizeof($pilD)-7));
					}
					
					if (substr($pilE,0,3)=="<p>") {
						$pilE = trim(substr($pilE,3,sizeof($pilE)-7));
					}
					
					$pembahasan = "-";				
					if (sizeof($AllPil)>5)
					{
						$AllPil[5] = trim($AllPil[5]);					
						$pembahasan = substr($AllPil[5],strpos($AllPil[5],"#BAHASAN#")+9);	
						$pembahasan = substr($pembahasan,0,strpos($pembahasan,"#END-BAHASAN#"));
					}		
					
					$jawaban = 0;
					
					if ($pilA[0]=="*") 
					{
						$pilA = trim(substr($pilA, 1, strlen($pilA)-1));
						$jawaban = 1;
					} else if ($pilB[0]=="*") 
					{
						$pilB = trim(substr($pilB, 1, strlen($pilB)-1));
						$jawaban = 2;
					} else if ($pilC[0]=="*") 
					{
						$pilC = trim(substr($pilC, 1, strlen($pilC)-1));
						$jawaban = 3;
					} else if ($pilD[0]=="*") 
					{
						$pilD = trim(substr($pilD, 1, strlen($pilD)-1));
						$jawaban = 4;
					} else if ($pilE[0]=="*") 
					{
						$pilE = trim(substr($pilE, 1, strlen($pilE)-1));
						$jawaban = 5;
					}
					
					//save soal
					$query = "INSERT INTO DSOAL (KODE_SOAL, SOAL, PILA, PILB, PILC, PILD, PILE, JAWABAN, KODE_GROUP, PEMBAHASAN) VALUES ('".$kode."', '".$isiSoal."', '".$pilA."', '".$pilB."', '".$pilC."', '".$pilD."', '".$pilE."', ". $jawaban. ", '".$_REQUEST["cbGroup"]."', '".$pembahasan."')";											
					mysql_query($query,$dbConn);
				}								
			}
			
			$query="SELECT KODE_GROUP, NAMA_GROUP FROM GROUP_SOAL WHERE (1=1 ";
											
			if (isset($_SESSION["kode_soal"]))
			{
				$group = $_SESSION["group_soal"]."|".$_REQUEST["cbGroup"];
				$kecuali = explode("|",$group);
				for ($i=0; $i<sizeof($kecuali); $i++)
				{
					$query = $query . " AND KODE_GROUP<>'".$kecuali[$i]."' ";
				}										
			}
			$query = $query .") ORDER BY KODE_GROUP ASC";
			$result = mysql_query($query);
			$num_rows = mysql_num_rows($result);
			if ($num_rows>0) {			
				header("location:NewSoal.php");	
			} else  {			
				header("location:soal.php");	
			}
	}
  else if ($_REQUEST['modeProses']=='UBAH'){
		$param = explode("|",$_REQUEST["cbNo"]);
	
		$pilA = $_REQUEST["isiA_".$_REQUEST["kode_soal"]."_".$_REQUEST["cbNo"]];
		$pilB = $_REQUEST["isiB_".$_REQUEST["kode_soal"]."_".$_REQUEST["cbNo"]];
		$pilC = $_REQUEST["isiC_".$_REQUEST["kode_soal"]."_".$_REQUEST["cbNo"]];
		$pilD = $_REQUEST["isiD_".$_REQUEST["kode_soal"]."_".$_REQUEST["cbNo"]];
		$pilE = $_REQUEST["isiE_".$_REQUEST["kode_soal"]."_".$_REQUEST["cbNo"]];
		$soal = $_REQUEST["soal_".$_REQUEST["kode_soal"]."_".$_REQUEST["cbNo"]];
		$pembahasan = $_REQUEST["pembahasan_".$_REQUEST["kode_soal"]."_".$_REQUEST["cbNo"]];
		
		if (substr($pilA,0,3)=="<p>") {
			$pilA = trim(substr($pilA,3,sizeof($pilA)-7));
		}
		
		if (substr($pilB,0,3)=="<p>") {
			$pilB = trim(substr($pilB,3,sizeof($pilB)-7));
		}
		
		if (substr($pilC,0,3)=="<p>") {
			$pilC = trim(substr($pilC,3,sizeof($pilC)-7));
		}
		
		if (substr($pilD,0,3)=="<p>") {
			$pilD = trim(substr($pilD,3,sizeof($pilD)-7));
		}
		
		if (substr($pilE,0,3)=="<p>") {
			$pilE = trim(substr($pilE,3,sizeof($pilE)-7));
		}
		
		if (substr($soal,0,3)=="<p>") {
			$soal = trim(substr($soal,3,sizeof($soal)-7));
		}
		
		$query = "UPDATE DSOAL SET SOAL='".$soal."', PILA='".$pilA."', PILB='".$pilB."', PILC='".$pilC."', PILD='".$pilD."', PILE='".$pilE."', JAWABAN=".$_REQUEST["pil_".$_REQUEST["kode_soal"]."_".$_REQUEST["cbNo"]].", PEMBAHASAN='".$pembahasan."' WHERE KODE_SOAL='".$_REQUEST["kode_soal"]."' AND NO_URUT='".$_REQUEST["cbNo"]."'";									
		mysql_query($query);
		
		if (isset($_REQUEST["bacaan_".$_REQUEST["kode_soal"]]))
		{
			//update bacaan
			$isi_bacaan = $_REQUEST["bacaan_".$_REQUEST["kode_soal"]];
			$start_num = $_REQUEST["edStart"];
			$end_num = $_REQUEST["edEnd"];
			$query = "UPDATE BACAAN SET ISI_BACAAN='".$isi_bacaan."', START_NUM=".$start_num.", END_NUM=".$end_num." WHERE KODE_SOAL='".$_REQUEST["kode_soal"]."' AND KODE_BACAAN='".$_REQUEST["kode_bacaan"]."'";									
			mysql_query($query);
		}

		header("location:EditSoal.php?sukses=".$_REQUEST["cbNo"]."&kode_soal=".$_REQUEST["kode_soal"]);
	}
?>