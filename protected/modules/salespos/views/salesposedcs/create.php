<?php
/* @var $this SalesposedcsController */
/* @var $model Salesposedcs */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Mesin EDC</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>