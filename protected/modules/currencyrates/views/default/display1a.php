<?php
/* @var $this CurrencyratesController */
/* @var $model Currencyrates */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Pencarian Nilai Tukar',
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
	$('#currencyrates-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Penentuan Nilai Tukar Mata Uang Asing</h1>

<?php 
	//if (isset($data)) {
		$mydp = new CArrayDataProvider($data, array(
			'id'=>'id'
			));	
		$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'displaycurrencyrates-grid',
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
				'header'=>'Nama Mata Uang',
				'name'=>'name',
				'type'=>'ntext',
			),
			array(
				'header'=>'Nilai Tukar',
				'name'=>'rate',
				'type'=>'number'
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
