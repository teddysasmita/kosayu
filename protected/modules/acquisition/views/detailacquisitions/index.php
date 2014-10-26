<?php
/* @var $this DetailacquisitionsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailacquisitions',
);

$this->menu=array(
	array('label'=>'Create Detailacquisitions', 'url'=>array('create')),
	array('label'=>'Manage Detailacquisitions', 'url'=>array('admin')),
);
?>

<h1>Detil Akuisisi Barang dan Nomor Seri</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
