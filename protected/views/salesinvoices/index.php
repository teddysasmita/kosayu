<?php
/* @var $this SalesinvoicesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Salesinvoices',
);

$this->menu=array(
	array('label'=>'Create Salesinvoices', 'url'=>array('create')),
	array('label'=>'Manage Salesinvoices', 'url'=>array('admin')),
);
?>

<h1>Salesinvoices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
