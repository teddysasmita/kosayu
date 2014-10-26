<?php
/* @var $this DetailsalesordersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailsalesorders',
);

$this->menu=array(
	array('label'=>'Create Detailsalesorders', 'url'=>array('create')),
	array('label'=>'Manage Detailsalesorders', 'url'=>array('admin')),
);
?>

<h1>Detailsalesorders</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
