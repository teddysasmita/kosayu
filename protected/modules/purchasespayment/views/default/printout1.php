<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Print Out Pembayaran Pemasok</title>
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
		width: 170mm;
		border: solid thin;
		border-collapse: collapse;
	}
	#detailtable { 
		width: 170mm;
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
		font-family: "WST_Engl", sans-serif;
	}
	.infofield {
		padding: 2mm;
		border: none;
		margin: 0;
		font-family: "WST_Engl", sans-serif;
	}
	.detailrow {
		padding: 1mm;
	}
	.detailcol {
		padding: 1mm;
		border: solid thin;
		margin: 0;
		font-size: 8pt;
		font-family: "WST_Engl", sans-serif; 
	}
	.numbercol {
		text-align: right;
	}
	.vspace {
		height: 3mm;
	}
	
	.hspace {
		width: 40mm;
	}
	
	.signature {
		height: 20mm;
		width: 50mm;
		text-align: center;
	}
	h1, h2 {
		font-family: "WST_Engl", sans-serif;
	}
</style>
</head>
<body>
	<h1>Tanda Terima</h1>
	<table id="maintable">
	<?php 
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Nomor Urut<td>:<td class=\"infovalue\">".$model->regnum;
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Tanggal<td>:<td class=\"infovalue\">".$model->idatetime;
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Pemasok<td>:<td class=\"infovalue\">".
		lookup::SupplierNameFromSupplierID($model->idsupplier);
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Total<td>:<td class=\"infovalue numbercol\">".
		number_format($model->total);
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Potongan<td>:<td class=\"infovalue numbercol\">".
		number_format($model->discount);
	echo "<tr class=\"mainrow\"><td class=\"infofield\">Biaya Label<td>:<td class=\"infovalue numbercol\">".
		number_format($model->labelcost);
	?>
	</table>
	<div class="vspace"></div>
	<h2>Daftar Retur</h2>
	<table id="detailtable">
	<tr>
	<th class="detailcol">No Retur Beli
	<th class="detailcol">Total
	<?php 
	foreach ($details as $dt) {
		echo "<tr class=\"detailrow\">";
		echo "<td class=\"detailcol\">${dt['regnum']}";
		echo "<td class=\"detailcol numbercol\">".number_format($dt['total']);
	}
	?>
	</table>
	<div class="vspace"></div>
	<h2>Daftar Nota Beli</h2>
	<table id="detailtable">
	<tr>
	<th class="detailcol">No Nota
	<th class="detailcol">Potongan
	<th class="detailcol">Sisa Tagihan
	<th class="detailcol">Biaya Label
	<th class="detailcol">Dibayar
	<?php 
	foreach ($details2 as $dt) {
		echo "<tr class=\"detailrow\">";
		echo "<td class=\"detailcol\">${dt['regnum']}";
		echo "<td class=\"detailcol numbercol\">".number_format($dt['discount']);
		echo "<td class=\"detailcol numbercol\">".number_format($dt['total']);
		echo "<td class=\"detailcol numbercol\">".number_format($dt['labelcost']);
		echo "<td class=\"detailcol numbercol\">".number_format($dt['amount']);
	}
	?>
	</table>
	<div class="vspace"></div>
	<h2>Cara Bayar</h2>
	<table id="detailtable">
	<tr>
	<th class="detailcol">Metode
	<th class="detailcol">Jumlah
	<?php 
	foreach ($details3 as $dt) {
		echo "<tr class=\"detailrow\">";
		echo "<td class=\"detailcol\">".lookup::getMethod($dt['method']);
		echo "<td class=\"detailcol numbercol\">".number_format($dt['amount']);
	}
	?>
	</table>
	<div class="vspace"></div>
	<table id="signaturetable">
	<tr><td class="infofield signature">Tanda Tangan Pembayar<td class="hspace"><td class="infofield signature">Mengetahui
	<tr><td class="signature"><td class="hspace"><td class="signature">
	<tr><td class="infovalue signature"><?php echo lookup::SupplierNameFromSupplierID($model->idsupplier);?>
	<td class="hspace"><td class="infovalue signature"><?php echo lookup::UserNameFromUserID($model->userlog);?>
	</table>
</body>
</html>