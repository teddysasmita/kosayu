<?php
/* @var $this DetailitemsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailitems',
);

$this->menu=array(
	array('label'=>'Create Detailitems', 'url'=>array('create')),
	array('label'=>'Manage Detailitems', 'url'=>array('admin')),
);
?>

<h1>Detailitems</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
