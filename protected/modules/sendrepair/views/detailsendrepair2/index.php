<?php
/* @var $this Detailsendrepairs2Controller */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailsendrepairs2',
);

$this->menu=array(
	array('label'=>'Create Detailsendrepairs2', 'url'=>array('create')),
	array('label'=>'Manage Detailsendrepairs2', 'url'=>array('admin')),
);
?>

<h1>Detil Pengiriman Barang untuk Perbaikan 2</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
