<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Laporan Penjualan Global Berdasar Pemasok'=>array('salesposreport/create3'),
	'Lihat Data'
);

$this->menu=array(
		array('label'=>'Export ke XL', 'url'=>array('getexcel3')),
);

?>

<h1>Laporan Penjualan Global Berdasar Pemasok</h1>

<h2>
<?php
	echo "Periode ".$startdate." hingga ".$enddate;
?>
</h2>

<?php 
	
	$dataProvider=new CArrayDataProvider($data,array(
		'totalItemCount'=>count($data),
		'keyField'=>'iddetail',
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
						'name'=>'suppliername',
				),
				array(
						'header'=>'Qty',
						'name'=>'qty',
						'type'=>'number',
				),
				array(
						'header'=>'Bruto',
						'name'=>'totalsold',
						'type'=>'number',
				),
				array(
						'header'=>'Potongan',
						'name'=>'totaldisc',
						'type'=>'number',
				),
				/*
				*/
				array(
						'header'=>'Harga Beli',
						'name'=>'totalcog',
						'type'=>'number',
				),
				array(
						'header'=>'Margin',
						'name'=>'totalgain',
						'type'=>'number',
				),
				/*
				array(
						'header'=>'Netto',
						'name'=>'nettotal',
						'type'=>'number',
				),
				
				array(
						'header'=>'Margin',
						'name'=>'margin',
						'type'=>'number',
				),*/
		),
));
	
$total = array();
$temp['id'] = 1;
$temp['totalsales'] = 0;
$temp['totaldisc'] = 0;
$temp['totalcog'] = 0;
$temp['totalgain'] = 0;
/*$temp['totalnet'] = 0;

*/
$total[] = $temp;

foreach($data as $d) {
	$total[0]['totalsales'] += $d['totalsold'];	
	$total[0]['totaldisc'] += $d['totaldisc'];
	$total[0]['totalcog'] += $d['totalcog'];
	$total[0]['totalgain'] += $d['totalgain'];
	/*$total[0]['totalnet'] += $d['nettotal'];
	;*/
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
						'header'=>'Total Beli',
						'name'=>'totalcog',
						'type'=>'number',
				),
				array(
						'header'=>'Total Margin',
						'name'=>'totalgain',
						'type'=>'number',
				),
				/*
				array(
						'header'=>'Total Netto',
						'name'=>'totalnet',
						'type'=>'number',
				),
				
				
				*/
		),
));

?>
