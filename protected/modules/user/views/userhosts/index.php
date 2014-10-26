<?php
/* @var $this UserhostsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Userhosts',
);

$this->menu=array(
	array('label'=>'Create Userhosts', 'url'=>array('create')),
	array('label'=>'Manage Userhosts', 'url'=>array('admin')),
);
?>

<h1>Userhosts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
