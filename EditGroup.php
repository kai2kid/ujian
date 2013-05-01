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
 ?>
 <html xmlns="http://www.w3.org/1999/xhtml">
<?php include("_head.php"); ?>
<body>
<center>
  <div id="header"></div>
		<div id="main" class="post">			
			<h2 class="title"><span>Detail Group</span></h2>
      <form id="formGroup" name="formGroup" method="post" action="SaveGroup.php">
        <input type='hidden' name='mode' id='mode' value='update'>
			<?php				
				echo "<input type='hidden' name='kode_group' id='kode_group' value='".$_REQUEST["kode"]."'>";			 
			?>
					<center>
					<div style="width: 598px; height: auto;">
					<table width="600px" cellpadding="1" cellspacing="1" border="0" style="margin-top: 10px; margin-bottom: 10px;">
						<tr>
							<td class="log2" style="text-align:right" ><b>Nama Group :</b></td>								
   						    <td class="log2"><input id="nama_group" type="text" name="nama_group" size="45" value="<?php echo $_REQUEST["nama"];  ?>"/></td>
						</tr>																	    				
						<tr>
							<td class="log2">&nbsp;</td>
							<td class="log2">
								<input id="btnOK" type="submit" name="btnOK" value="Ubah Nama Group" />
							</td>
						</tr>
					</table>
					</div>
					</center>

			</form>			
			<div class="meta">
				<br/>
				<center><a href="group.php">Kembali ke halaman Group Soal</a></center>
			</div>
		</div>
  <?php include("_footer.php"); ?>
  </center>
</body>
</html>