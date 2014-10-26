<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
   'Penyesuaian'=>array('/site/setting'),
	'Daftar Operator'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Penyesuaian Operator</h1>

<?php
   echo $this->renderPartial('_form', array('model'=>$model));
?>