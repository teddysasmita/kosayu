<?php
/* @var $this PrivsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Privs',
);

$this->menu=array(
	array('label'=>'Create Privs', 'url'=>array('create')),
	array('label'=>'Manage Privs', 'url'=>array('admin')),
);
?>

<h1>Privs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
