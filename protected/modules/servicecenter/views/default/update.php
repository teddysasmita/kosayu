<?php
/* @var $this ServicecentersController */
/* @var $model Servicecenters */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
   'Lihat Data'=>array('view', 'id'=>$model->id),
   'Ubah Data'
);

$this->menu=array(
);
?>

<h1>Service Center</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>