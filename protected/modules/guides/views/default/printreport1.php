<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Cetak Aktivitas Guide</title>
<style>
	@page { size: <?php echo '215'; ?>mm <?php echo '297'; ?>mm;
		margin-bottom: <?php echo '10'; ?>mm;
		margin-top: <?php echo '10'; ?>mm; 
		margin-right: <?php echo '10'; ?>mm;
		margin-left: <?php echo '10'; ?>mm;
		page-break-before: 5mm;
	}
	
	#maintable { 
		border: solid thin;
		border-collapse: collapse;
		padding: 0mm
	}	 
	.mainrow {
		padding: 2mm;
	}
	.maincol {
		padding: 2mm;
		border: solid thin;
		margin: 0;
	}
	#col3 {
		text-align: right;
	}
	th {
		padding: 2mm;
		border: solid thin;
	}
</style>
</head>
<body>
	<h1>Laporan Aktivitas Guide</h1>
	<h2><?php echo $model->firstname.' '.$model->lastname; ?></h2>
	<table id="maintable">
	<thead id="mainhtableead">
	<tr>
	<th>Nomor Sticker</th>
	<th>Tanggal Sticker</th>
	<th>Total Penjualan</th>
	</tr>
	</thead>
	<?php 
	foreach ($data as $row) {
		echo "<tr class=\"mainrow\">";
		echo "<td class=\"maincol\">${row['stickernum']}</td>";
		echo "<td class=\"maincol\">${row['stickerdate']}</td>";
		echo "<td class=\"maincol\" id=\"col3\">".number_format($row['totalsales'])."</td></tr>";	
	};
	?>
	</table>
</body>
</html>