<?php
/* @var $this SalesvouchersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
   array('label'=>'Data yang telah terhapus', 'url'=>array('deleted')),
		array('label'=>'Export Nama ke Xcl', 'url'=>array('export2xcl')),
);
?>

<h1>Voucher / Kupon</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
