<?php
/* @var $this ServicecentersController */
/* @var $model Servicecenters */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Service Center</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>