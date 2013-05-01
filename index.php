<?php session_start(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("_head.php"); ?>
<body>
<?php
if (isset($_GET['logout']) && $_GET['logout']==true){    
	$_SESSION['NAMA']='';
	$_SESSION['JENIS']='';
	$_SESSION['USER']='';
}  
?>  
	<center>
	<div id="header"></div>
	<div id="main" style="text-align:center">
		<div id="login" class="boxed">
			<div class="content">			  
			  <form id="formLogin" method="post" action="Cek_SignIn.php">
					<table border="0" style="margin-left:auto; margin-right: auto; width:500px;">
						<tr>
							<td align="right" width=210px><b>Username</b></td>
							<td>:</td>
							<td><input id="tuname" type="text" name="tuname" value="" style="padding-left:10px;"/></td>
						</tr>
						<tr>
							<td align="right"><b>Password</b></td>
							<td>:</td>
							<td><input id="tpass" type="password" name="tpass" value="" style="padding-left:10px;" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><input id="btnLogin" type="submit" name="btnLogin" value="Login" style="margin-left:90px;" /></td>
						</tr>
						<tr>
							<td colspan="3" align="center"><label style='color:red; font-size:8pt;' id="errmsg"></label></td>							
						</tr>
					</table>					
			  </form>
			</div>
		</div>
	</div>	
<?php include("_footer.php"); ?>
	</center>
</body>
</html>