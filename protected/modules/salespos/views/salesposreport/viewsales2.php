<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Laporan Detil Pendapatan Kasir'=>array('salesposreport/create2'),
	'Lihat Data'
);

$this->menu=array(
	
);
?>

<h1>Laporan Detil Pendapatan Kasir</h1>

<h2>
<?php
	echo "Periode ".$startdate." hingga ".$enddate;
?>
</h2>

<?php 
	
	$dataProvider=new CArrayDataProvider($data,array(
		'totalItemCount'=>count($data),
		'pagination'=>array(
				'pageSize'=>20,
			),
	));
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
				array(
						'header'=>'Tanggal',
						'name'=>'idatetime',
				),
				array(
						'header'=>'Nomor Nota',
						'name'=>'regnum',
				),
				array(
						'header'=>'Kasir',
						'name'=>'idcashier',
						'value'=>"lookup::UserNameFromUserID(\$data['idcashier'])",
				),
				array(
						'header'=>'Nomor Sticker',
						'name'=>'idsticker',
				),
				array(
						'header'=>'Total Nota',
						'name'=>'totalsales',
						'type'=>'number',
				),
				array(
						'header'=>'Tunai',
						'name'=>'cash',
						'type'=>'number',
				),
				array(
						'header'=>'Kartu Debit',
						'name'=>'debitcard',
						'type'=>'number',
				),
				array(
						'header'=>'Kartu Kredit',
						'name'=>'creditcard',
						'type'=>'number',
				),
				array(
						'header'=>'Voucher',
						'name'=>'voucher',
						'type'=>'number',
				),	
				array(
						'header'=>'Retur',
						'name'=>'retur',
						'type'=>'number',
				),
				array(
						'header'=>'Kembalian',
						'name'=>'cashreturn',
						'type'=>'number',
				),
		),
));
	
$total = array();
$temp['id'] = 1;
$temp['totaljual'] = 0;
$temp['totalkembali'] = 0;
$temp['tunai'] = 0;
$temp['kartukredit'] = 0;
$temp['kartudebit'] = 0;
$temp['retur'] = 0;
$temp['voucher'] = 0;
$temp['totalmasuk'] = 0;
$total[] = $temp;

foreach($data as $d) {
	$total[0]['tunai'] += $d['cash'];	
	$total[0]['kartudebit'] += $d['debitcard'];
	$total[0]['kartukredit'] += $d['creditcard'];
	$total[0]['retur'] += $d['retur'];
	$total[0]['voucher'] += $d['voucher'];
	$total[0]['totaljual'] += $d['totalsales'];
	$total[0]['totalkembali'] += $d['cashreturn'];
	$total[0]['totalmasuk'] += ($d['cash'] + $d['debitcard'] + $d['creditcard'] 
			+ $d['retur'] + $d['voucher'] - $d['cashreturn']);
}


$dataProvider2=new CArrayDataProvider($total,array(
		'totalItemCount'=>count($total),
));

$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider2,
		'columns'=>array(
				array(
						'header'=>'Total Nota',
						'name'=>'totaljual',
						'type'=>'number',
				),
				array(
						'header'=>'Total Terima',
						'name'=>'totalmasuk',
						'type'=>'number',
				),
				array(
						'header'=>'Total Kembalian',
						'name'=>'totalkembali',
						'type'=>'number',
				),
				array(
						'header'=>'Total Tunai',
						'name'=>'tunai',
						'type'=>'number',
				),
				array(
						'header'=>'Total Kartu Debit',
						'name'=>'kartudebit',
						'type'=>'number',
				),
				array(
						'header'=>'Total Kartu Kredit',
						'name'=>'kartukredit',
						'type'=>'number',
				),
				array(
						'header'=>'Total Voucher',
						'name'=>'voucher',
						'type'=>'number',
				),
				array(
						'header'=>'Total Retur',
						'name'=>'retur',
						'type'=>'number',
				),
		),
));

?>
