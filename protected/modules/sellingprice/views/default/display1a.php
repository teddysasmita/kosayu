<?php
/* @var $this SellingpricesController */
/* @var $model Sellingprices */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Pencarian Harga',
);

$this->menu=array(
	//array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sellingprices-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Penentuan Harga Jual</h1>

<?php 
	//if (isset($data)) {
		$mydp = new CArrayDataProvider($data, array(
			'id'=>'id'
			));	
		$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'displaysellingprices-grid',
		'dataProvider'=>$mydp,
		'columns'=>array(
			//'id',
			array(
				'header'=>'Nomor Urut',
				'name'=>'regnum',
			),
			array(
				'header'=>'Tanggal',
				'name'=>'idatetime',
			),
			array(
				'header'=>'Nama Barang',
				'name'=>'name',
				'type'=>'ntext',
			),
			array(
				'header'=>'Harga Jual',
				'name'=>'normalprice',
				'type'=>'number'
			),
			array(
				'header'=>'Harga Minimum',
				'name'=>'minprice',
				'type'=>'number'
			),
			'approvalby',
			array(
				'header'=>'Userlog',
				'name'=>'userlog',
				'value'=>"lookup::UserNameFromUserID(\$data['userlog'])",
			),
			//'userlog',
			//'datetimelog',
			/*array(
				'class'=>'CButtonColumn',
			),*/
		),
		)); 
	//	}
?>
