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
	
	.maintable { 
		border: none;
	}	 
</style>
</head>
<body>
	<h1>Laporan Aktivitas Guide</h1>
	<h2><?php echo $model->firstname.' '.$model->lastname; ?></h2>
	<table id="maintable">
	<col id="col1"><col id="col2"><col id="col3">
	<thead id="mainhtableead">
	<tr>
	<th>Nomor Sticker</th>
	<th>Tanggal Sticker</th>
	<th>Total Penjualan</th>
	</tr>
	</thead>
	<?php 
	foreach ($data as $row) {
		echo "<tr><td>${row['stickernum']}</td><td>${row['stickerdate']}</td><td>".number_format($row['stickertotalsales'])."</td></tr>";	
	};
	?>
	</table>
</body>
</html>