<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/masterdata'),
	'Laporan Penjualan'=>array('salesposreport/create'),
	'Lihat Data'
);

$this->menu=array(
	
);
?>

<h1>Daftar Aktivitas Penjualan</h1>

<?php 
	
	$dataProvider=new CArrayDataProvider($data,array(
		'totalItemCount'=>$count,
	));
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
				array(
						'header'=>'Kasir',
						'name'=>'idcashier',
						'value'=>"lookup::UserNameFromUserID(\$data['idcashier'])",
				),
				array(
						'header'=>'Tanggal',
						'name'=>'idatetime',
				),
				array(
						'header'=>'Metode',
						'name'=>'method',
				),
				array(
						'header'=>'Jumlah',
						'name'=>'amount',
						'type'=>'number',
				),
		),
));


?>
