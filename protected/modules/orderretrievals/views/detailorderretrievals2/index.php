<?php
/* @var $this DetailorderretrievalsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailorderretrievals',
);

$this->menu=array(
	array('label'=>'Create Detailorderretrievals', 'url'=>array('create')),
	array('label'=>'Manage Detailorderretrievals', 'url'=>array('admin')),
);
?>

<h1>Detil Pengambilan Barang</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
