<?php
/* @var $this DetailstockentriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailstockentries',
);

$this->menu=array(
	array('label'=>'Create Detailstockentries', 'url'=>array('create')),
	array('label'=>'Manage Detailstockentries', 'url'=>array('admin')),
);
?>

<h1>Detailstockentries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
