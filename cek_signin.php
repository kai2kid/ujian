<?php include("ConnectionString.php"); ?>
<?php  
  session_start();  

  if ((isset($_POST['tuname']) && ($_POST['tuname']!="")) && (isset($_POST['tpass']) && ($_POST['tpass']!=""))){  	
	$user=$_POST['tuname'];
	$pass=md5($_POST['tpass']);

	$query="SELECT NAMA, JENIS FROM USER WHERE USERNAME='".$user."' AND PASSWORD='".$pass."'";
	$result=mysql_query($query);
	$jum=mysql_num_rows($result);	
	if ($jum!=0){
		$row=mysql_fetch_assoc($result);
		$_SESSION['NAMA'] = $row['NAMA'];
		$_SESSION['JENIS'] = $row['JENIS'];	
		$_SESSION['USER'] = $user;		
		
		if ($row['JENIS']=='S'){
			header("location:indexsiswa.php");
		} else if ($row['JENIS']=='G'){
			header("location:indexguru.php");
		} else if ($row['JENIS']=='A'){
			header("location:indexadmin.php");
		} else {			
			header("location:index.php?salah=true");
		}			
	} else {				
		header("location:index.php?salah=true");
	}    
  }
  else {
  }
?>