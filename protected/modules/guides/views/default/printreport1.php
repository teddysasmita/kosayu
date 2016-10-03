<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Cetak Aktivitas Guide</title>
<style>
	@page { size: <?php echo $model->paperwidth; ?>mm <?php echo $model->paperheight; ?>mm;
		margin-bottom: <?php echo $model->paperbotm; ?>mm;
		margin-top: <?php echo $model->paperbotm; ?>mm; 
		margin-right: <?php echo $model->papersidem; ?>mm;
		margin-left: <?php echo $model->papersidem+10; ?>mm;
		page-break-before: 5mm;
	}
	
	.maintable { 
		/*margin-left: <?php echo $model->paperwidth - ( 3 * $model->papersidem) - $model->labelwidth - (2 * $model->labelsidem); ?>mm;*/ 	
		border: none;
	}
	
	.guidetablerow { 
		height: <?php echo $model->labelheight; ?>mm;
		margin: <?php echo $model->labelbotm; ?>mm; 
	}
	.guidetablecol { 
		width: <?php echo $model->labelwidth; ?>mm;
		margin: <?php echo $model->labelsidem; ?>mm;
		 
	}
	.guidetablecell {  
		border: solid thin;
		-webkit-print-color-adjust: exact;
	 }
	 .cardcontent {
	 	background-color: yellow;
	 	height: <?php echo $model->labelheight-4; ?>mm;
	 	width: <?php echo $model->labelwidth-4; ?>mm;
	 	margin-top: <?php echo $model->labelbotm; ?>mm;
	 	margin-bottom: <?php echo $model->labelbotm; ?>mm;
	 	margin-right: <?php echo $model->labelsidem; ?>mm;
	 	margin-left: <?php echo $model->labelsidem; ?>mm;
	 	float: left;
	 	padding: 2mm;
	 	border: solid thin;
	 	page-break-inside: avoid;
	 }
	 .emptycontent {
	 	height: <?php echo $model->labelheight-2; ?>mm;
	 	width: <?php echo $model->labelwidth-2; ?>mm;
	 }
	 
</style>
</head>
<body>
	<h1>Laporan Aktivitas Guide</h1>
	<h2><?php echo $model->name; ?></h2>
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