<?php
/* @var $this SalesposbanksController */
/* @var $model Salesposbanks */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Bank dan Pembiayaan</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>