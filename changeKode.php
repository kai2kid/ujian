<?php include("ConnectionString.php"); ?>
<?php	
	echo '<table  border="1" cellpadding="5" cellspacing="0">';
	echo '<tr>';
		echo '<td class="judulTable" width="165px" align="center"><b>No. Induk</b></td>';
		echo '<td class="judulTable" width="315px" align="center"><b>Nama Lengkap</b></td>';
		echo '<td class="judulTable" width="120px" align="center"><b>Nilai</b></td>';										
	echo '</tr>';				
	$query = "SELECT NILAI.USERNAME, USER.NAMA, NILAI FROM NILAI, USER WHERE USER.USERNAME=NILAI.USERNAME AND ";
	$query = $query . "NILAI.KODE_UJIAN='".$_REQUEST["kode"]."' ";
	$query = $query . "ORDER BY USER.USERNAME";					
	$res = mysql_query($query,$dbConn);
	if ($res) {
		while($row=mysql_fetch_assoc($res)){
		  echo("<tr>");				
		  echo('<td class="log2" valign="top" align="center">'. $row['USERNAME'] ."</td>");
		  echo('<td class="log2" valign="top">'. strtoupper($row['NAMA']) ."</td>");
		  echo('<td class="log2" valign="top" align="center">'. $row['NILAI'] ."</td>");					  					 
		  echo("</tr>");
		}
	}					
	echo '</table>';
?>