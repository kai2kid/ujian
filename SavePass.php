<?php
 session_start();
 include("ConnectionString.php"); 
  if ($_SESSION['JENIS']!='G'){
    header("location:index.php");
  }
  
  if (isset($_REQUEST["edOldPass"]))
  {
		if ($_REQUEST["edNewPass"]==$_REQUEST["edNewCPass"])
		{
			//cek password lama
			$query = "SELECT * FROM USER WHERE USERNAME='".$_SESSION['USER']."' AND PASSWORD='".md5($_REQUEST["edOldPass"])."'";
			$result = mysql_query($query);
			$jum = mysql_num_rows($result);
			
			if ($jum>0){
				$query = "UPDATE USER SET PASSWORD='".md5($_REQUEST["edNewPass"])."' WHERE USERNAME='".$_SESSION['USER']."'";
				mysql_query($query);
				header("location:UbahPass.php?msg=1");	
			} else {
				header("location:UbahPass.php?msg=2");	
			}
		} else{
			header("location:UbahPass.php?msg=3");	
		}
  } else {
		header("location:UbahPass.php?msg=4");
  }
?>