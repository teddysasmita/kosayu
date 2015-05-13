<?php
/* @var $this Detil Pembayaran Gaji KaryawanController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detil Pembayaran Gaji Karyawan',
);

$this->menu=array(
	array('label'=>'Create Detil Pembayaran Gaji Karyawan', 'url'=>array('create')),
	array('label'=>'Manage Detil Pembayaran Gaji Karyawan', 'url'=>array('admin')),
);
?>

<h1>Detil Pembayaran Gaji Karyawan</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
