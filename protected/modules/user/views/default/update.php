<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
   'Penyesuaian'=>array('/site/setting'),
	'Daftar'=>array('index'),
   'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Lihat Data', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Penyesuaian Operator</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>