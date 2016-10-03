<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Print Out Pembayaran Guide</title>
<style>
	@page { 
		size: <?php echo '100'; ?>mm <?php echo '140'; ?>mm portrait;
		margin-bottom: <?php echo '10'; ?>mm;
		margin-top: <?php echo '10'; ?>mm; 
		margin-right: <?php echo '10'; ?>mm;
		margin-left: <?php echo '10'; ?>mm;
		page-break-before: 5mm;
	}
	
	#maintable { 
		width: 120mm;
		border: solid thin;
		border-collapse: collapse;
	}
	#detailtable { 
		width: 120mm;
		border: solid thin;
		border-collapse: collapse;
	}	 
	.mainrow {
		padding: 2mm;
		border: solid thin;
	}
	.infovalue {
		padding: 2mm;
		border: none;
		margin: 0;
		font-family: monospace;
	}
	.infofield {
		padding: 2mm;
		border: none;
		margin: 0;
		font-family: monospace;
	}
	.detailrow {
		padding: 1mm;
	}
	.detailcol {
		padding: 1mm;
		border: solid thin;
		margin: 0;
		font-size: small;
		font-family: monospace; 
	}
	.numbercol {
		text-align: right;
	}
	#space {
		height: 3mm;
	}
	#signature {
		height: 20mm;
	}
</style>
</head>
<body>
	<h1>Tanda Terima</h1>
	<table id="maintable">
	<?php 
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Nomor Urut<td>:<td class=\"infovalue\">".$model->regnum;
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Tanggal<td>:<td class=\"infovalue\">".$model->idatetime;
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Total Komisi<td>:<td class=\"infovalue numbercol\">".number_format($model->commission);
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Deposit<td>:<td class=\"infovalue numbercol\">".number_format($model->deposit);
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Jumlah Diambil<td>:<td class=\"infovalue numbercol\">".number_format($model->amount);
	?>
	</table>
	<div id='space'></div>
	<table id="detailtable">
	<tr>
	<th class="detailcol">No Sticker
	<th class="detailcol">No Nota
	<th class="detailcol">Total
	<th class="detailcol">Disc
	<th class="detailcol">Komisi
	<?php 
	foreach ($details as $dt) {
		echo "<tr class=\"detailrow\">";
		echo "<td class=\"detailcol\">${dt['stickernum']}";
		echo "<td class=\"detailcol\">${dt['regnum']}";
		echo "<td class=\"detailcol numbercol\">".number_format($dt['totalsales']);
		echo "<td class=\"detailcol numbercol\">".number_format($dt['totaldisc']);
		echo "<td class=\"detailcol numbercol\">".number_format($dt['commission']);
	}
	?>
	</table>
	<div id='space'></div>
	<table id="signaturetable">
	<tr><td class="infofield">Tanda Tangan
	<tr><td id="signature">
	<tr><td class="infovalue"><?php echo lookup::GuideNameFromID($model->idguide);?>
	</table>
</body>
</html>