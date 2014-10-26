<?php
/* @var $this DetailsalesordersController */
/* @var $model Detailsalesorders */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Lihat Data'=>array('default/view', 'id'=>$model->id),
      'Lihat Detil',
);

$this->menu=array(
	//array('label'=>'List Detailsalesorders', 'url'=>array('index')),
	//array('label'=>'Create Detailsalesorders', 'url'=>array('create')),
	array('label'=>'Ubah Detil', 'url'=>array('update', 'iddetail'=>$model->iddetail)),
	array('label'=>'Hapus Detil', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','iddetail'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	//array('label'=>'Manage Detailsalesorders', 'url'=>array('admin')),
   array('label'=>'Sejarah', 'url'=>array('history','iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Pemesanan oleh Pelanggan</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'itemname',
		'qty',
	),
)); ?>
