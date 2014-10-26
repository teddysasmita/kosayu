<?php
/* @var $this DetailpurchasesmemosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailpurchasesmemos',
);

$this->menu=array(
	array('label'=>'Create Detailpurchasesmemos', 'url'=>array('create')),
	array('label'=>'Manage Detailpurchasesmemos', 'url'=>array('admin')),
);
?>

<h1>Detailpurchasesmemos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
