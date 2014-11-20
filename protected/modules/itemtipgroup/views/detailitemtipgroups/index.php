<?php
/* @var $this DetailitemtipgroupsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailitemtipgroups',
);

$this->menu=array(
	array('label'=>'Create Detailitemtipgroups', 'url'=>array('create')),
	array('label'=>'Manage Detailitemtipgroups', 'url'=>array('admin')),
);
?>

<h1>Detailitemtipgroups</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
