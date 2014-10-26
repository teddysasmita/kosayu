<?php
/* @var $this SuppliersController */
/* @var $model Suppliers */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
   'Lihat Data'=>array('view', 'id'=>$model->id),
   'Ubah Data'
);

$this->menu=array(
);
?>

<h1>Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>