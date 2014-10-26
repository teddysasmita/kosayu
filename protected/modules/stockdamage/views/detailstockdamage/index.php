<?php
/* @var $this DetailstockdamageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailstockdamage',
);

$this->menu=array(
	array('label'=>'Create Detailstockdamage', 'url'=>array('create')),
	array('label'=>'Manage Detailstockdamage', 'url'=>array('admin')),
);
?>

<h1>Detil Barang Rusak</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
