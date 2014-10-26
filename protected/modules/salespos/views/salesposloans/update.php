<?php
/* @var $this SalesposloansController */
/* @var $model Salesposloans */

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

<h1>Cicilan</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>