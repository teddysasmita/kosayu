<?php
/* @var $this DetailpartnersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailpartners',
);

$this->menu=array(
	array('label'=>'Create Detailpartners', 'url'=>array('create')),
	array('label'=>'Manage Detailpartners', 'url'=>array('admin')),
);
?>

<h1>Detailpartners</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
