<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/masterdata'),
	'Laporan Detil Pendapatan Kasir'=>array('salesposreport/create2'),
	'Lihat Data'
);

$this->menu=array(
	
);
?>

<h1>Laporan Detil Pendapatan Kasir</h1>

<h2>
<?php
	echo "Kasir: ".lookup::UserNameFromUserID($idcashier);
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
						'name'=>'idate',
				),
				array(
						'header'=>'Nomor Nota',
						'name'=>'regnum',
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
						'header'=>'Kembalian',
						'name'=>'cashreturn',
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
$total[] = $temp;

foreach($data as $d) {
	$total[0]['tunai'] += $d['cash'];	
	$total[0]['kartudebit'] += $d['debitcard'];
	$total[0]['kartukredit'] += $d['creditcard'];
	$total[0]['retur'] += $sd['retur'];
	$total[0]['voucher'] += $sd['voucher'];
	$total[0]['totaljual'] += $sd['totalsales'];
	$total[0]['totalkembali'] += $sd['cashreturn'];
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
						'header'=>'Total Kembalian',
						'name'=>'totalkembali',
						'type'=>'number',
				),
				array(
						'header'=>'Tunai',
						'name'=>'tunai',
						'type'=>'number',
				),
				array(
						'header'=>'Kartu Debit',
						'name'=>'kartudebit',
						'type'=>'number',
				),
				array(
						'header'=>'Kartu Kredit',
						'name'=>'kartukredit',
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
		),
));

?>
