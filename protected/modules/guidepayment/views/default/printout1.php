<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Print Out Pembayaran Guide</title>
<style>
	@page { size: <?php echo '140'; ?>mm <?php echo '100'; ?>mm;
		margin-bottom: <?php echo '10'; ?>mm;
		margin-top: <?php echo '10'; ?>mm; 
		margin-right: <?php echo '10'; ?>mm;
		margin-left: <?php echo '10'; ?>mm;
		page-break-before: 5mm;
	}
	
	#maintable { 
		border: solid thin;
		border-collapse: collapse;
	}
	#detailtable { 
		border: solid thin;
		border-collapse: collapse;
	}	 
	.mainrow {
		padding: 2mm;
	}
	.maincol {
		padding: 2mm;
		border: solid thin;
		margin: 0;
	}
	.detailrow {
		padding: 1mm;
	}
	.detailcol {
		padding: 1mm;
		border: solid thin;
		margin: 0;
	}
	.numbercol {
		text-align: right;
	}
</style>
</head>
<body>
	<h1>Tanda Terima</h1>
	<table id="maintable">
	<?php 
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Nomor Urut<td>:<td class=\"infovalue\">".$model->regnum;
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Tanggal<td>:<td class=\"infovalue\">".$model->idatetime;
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Total Komisi<td class=\"infovalue numbercol\">:<td>".number_format($model->commission);
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Deposit<td>:<td class=\"infovalue numbercol\">".number_format($model->deposit);
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Jumlah Diambil<td>:<td class=\"infovalue numbercol\">".number_format($model->amount);
	?>
	</table>
	<table id="detailtable">
	<?php 
	foreach ($details as $dt) {
		echo "<tr class=\"detailrow\">";
		echo "<td class=\"detailcol\">${dt['stickernum']}";
		echo "<td class=\"detailcol\">${dt['regnum']}";
		echo "<td class=\"detailcol\">".lookup::ItemNameFromItemID($dt['stickernum']);
		echo "<td class=\"detailcol numbercol\">${dt['qty']}";
		echo "<td class=\"detailcol numbercol\">${dt['amount']}";
	}
	?>
	</table>
	<table id="signaturetable">
	<tr><td>Tanda Tangan
	<tr><td id="signature">
	<tr><td><?php echo lookup::GuideNameFromID($model->idguide);?>
	</table>
</body>
</html>