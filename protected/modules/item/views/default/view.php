<?php
/* @var $this ItemsController */
/* @var $model Items */

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

<h1>Item Penjualan</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'code',
		array(
                   'name'=>'type',
                   'label'=>'Jenis',
                   'value'=>lookup::TypeToName($model['type']),
                ),   
		'name',
		'brand',
		'objects',
		'model',
		'attribute',
		array(
                   'name'=>'picture',
                   'label'=>'Foto',
                   'type'=>'raw',
                   //'value'=>  html_entity_decode(CHtml::image(Yii::app()->basePath.'/images/'.$model['picture'])),
                   'value'=>  html_entity_decode(CHtml::image(YIi::app()->baseUrl.'/images/'.$model['picture'],'Not Available')),
                ),
		array(
                   'name'=>'userlog',
                   'value'=>lookup::UserNameFromUserID($model->userlog)
                ),
		'datetimelog',
	),
)); ?>
