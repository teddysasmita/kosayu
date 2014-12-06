<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Barang Dagang'=>array('item'),
	'Lihat Data'=>array('view','id'=>$id),
	'Penjualan'
);

$this->menu=array(
	
);
?>

<h1>Laporan Penjualan Barang</h1>

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
						'header'=>'Kasir',
						'name'=>'userlog',
						'value'=>"lookup::UserNameFromUserID(\$data['userlog'])",
				),
				array(
						'header'=>'Jumlah',
						'name'=>'qty',
						'type'=>"number",
				),
				array(
						'header'=>'Harga',
						'name'=>'price',
						'type'=>'number'
				),
				array(
						'header'=>'Discount',
						'name'=>'discount',
						'type'=>'number'
				),
				array(
						'header'=>'Total Harga',
						'name'=>'totalprice',
						'type'=>'number'
				),
		),
));
	
	$total = array();
	$temp['id'] = 1;
	$temp['totalqty'] = 0;
	$temp['totalprice'] = 0;
	$temp['totaldisc'] = 0;
	$total[] = $temp;
	
	foreach($data as $d) {
		$total[0]['totalqty'] += $d['qty'];
		$total[0]['totalprice'] += $d['totalprice'];
		$total[0]['totaldisc'] += $d['discount'];
	}
	
	
	$dataProvider2=new CArrayDataProvider($total,array(
			'totalItemCount'=>count($total),
	));
	
	$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$dataProvider2,
			'columns'=>array(
					array(
							'header'=>'Total Qty',
							'name'=>'totalqty',
							'type'=>'number',
					),
					array(
							'header'=>'Total Harga',
							'name'=>'totalprice',
							'type'=>'number',
					),
					array(
							'header'=>'Total Discount',
							'name'=>'totaldisc',
							'type'=>'number',
					),
			),
	));	
	
?>
