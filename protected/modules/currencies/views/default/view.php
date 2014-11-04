<?php
/* @var $this CurrenciesController */
/* @var $model Currencies */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
	array('label'=>'Cetak Kartu Stok', 'url'=>array('printstockcard', 'id'=>$model->id)),
	array('label'=>'Cetak Kartu Stok Kosong', 'url'=>array('printblankstockcard', 'id'=>$model->id)),
);
?>

<h1>Mata Uang Asing</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'symbol',
		'name',
		array(
                   'name'=>'userlog',
                   'value'=>lookup::UserNameFromUserID($model->userlog)
                ),
		'datetimelog',
	),
)); ?>
