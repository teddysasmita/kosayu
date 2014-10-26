<?php
/* @var $this DetailsalesreplaceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailsalesreplace',
);

$this->menu=array(
	array('label'=>'Create Detailsalesreplace', 'url'=>array('create')),
	array('label'=>'Manage Detailsalesreplace', 'url'=>array('admin')),
);
?>

<h1>Detailsalesreplace</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
