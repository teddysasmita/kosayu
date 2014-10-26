<?php
/* @var $this DetailsalesinvoicesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailsalesinvoices',
);

$this->menu=array(
	array('label'=>'Create Detailsalesinvoices', 'url'=>array('create')),
	array('label'=>'Manage Detailsalesinvoices', 'url'=>array('admin')),
);
?>

<h1>Detailsalesinvoices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
