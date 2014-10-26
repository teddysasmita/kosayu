<?php
/* @var $this AuthItemChildController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Auth Item Children',
);

$this->menu=array(
	array('label'=>'Create AuthItemChild', 'url'=>array('create')),
	array('label'=>'Manage AuthItemChild', 'url'=>array('admin')),
);
?>

<h1>Auth Item Children</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
