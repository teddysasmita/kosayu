<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/masterdata'),
	'Laporan Pendapatan Kasir'=>array('salesposreport/create'),
	'Lihat Data'
);

$this->menu=array(
	
);
?>

<h1>Laporan Pendapatan Kasir</h1>

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
						'header'=>'Kasir',
						'name'=>'idcashier',
						'value'=>"lookup::UserNameFromUserID(\$data['idcashier'])",
				),
				array(
						'header'=>'Metode',
						'name'=>'method',
						'value'=>"lookup::getMethod(\$data['method'])",
				),
				array(
						'header'=>'Kurs',
						'name'=>'idcurr',
						'value'=>"lookup::CurrencySymbolFromID(\$data['idcurr'])",
				),
				array(
						'header'=>'Jumlah',
						'name'=>'total',
						'type'=>'number',
				),
		),
));
	
?>
<H1>
Total : <?php
	$total = 0; 
	foreach($data as $d) {
		$total = $total + $d['total'];
	};
	echo number_format($total);
?>

</H1>
