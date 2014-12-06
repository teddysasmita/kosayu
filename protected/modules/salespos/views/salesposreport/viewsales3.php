<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Laporan Penjualan Tiap Pemasok'=>array('salesposreport/create3'),
	'Lihat Data'
);

$this->menu=array(
	
);
?>

<h1>Laporan Penjualan Tiap Pemasok</h1>

<h2>
<?php
	echo "Periode ".$startdate." hingga ".$enddate;
?>
</h2>

<?php 
	
	$dataProvider=new CArrayDataProvider($data,array(
		'totalItemCount'=>count($data),
		'pagination'=>array(
				'pageSize'=>25,
			),
	));
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
				array(
						'header'=>'Kode',
						'name'=>'scode',
				),
				array(
						'header'=>'Pemasok',
						'name'=>'name',
				),
				array(
						'header'=>'Bruto',
						'name'=>'totalprice',
						'type'=>'number',
				),
				array(
						'header'=>'Potongan',
						'name'=>'totaldiscount',
						'type'=>'number',
				),
				array(
						'header'=>'Netto',
						'name'=>'nettotal',
						'type'=>'number',
				),
				array(
						'header'=>'Harga Beli',
						'name'=>'totalbuyprice',
						'type'=>'number',
				),
				array(
						'header'=>'Laba',
						'name'=>'profit',
						'type'=>'number',
				),
				array(
						'header'=>'Margin',
						'name'=>'margin',
						'type'=>'number',
				),
		),
));
	
$total = array();
$temp['id'] = 1;
$temp['totalsales'] = 0;
$temp['totaldisc'] = 0;
$temp['totalnet'] = 0;
$total[] = $temp;

foreach($data as $d) {
	$total[0]['totalsales'] += $d['totalprice'];	
	$total[0]['totaldisc'] += $d['totaldiscount'];
	$total[0]['totalnet'] += $d['nettotal'];
}


$dataProvider2=new CArrayDataProvider($total,array(
		'totalItemCount'=>count($total),
));

$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider2,
		'columns'=>array(
				array(
						'header'=>'Total Bruto',
						'name'=>'totalsales',
						'type'=>'number',
				),
				array(
						'header'=>'Total Potongan',
						'name'=>'totaldisc',
						'type'=>'number',
				),
				array(
						'header'=>'Total Netto',
						'name'=>'totalnet',
						'type'=>'number',
				),
		),
));

?>
