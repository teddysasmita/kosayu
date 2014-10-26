<?php
/* @var $this PurchasesstockentriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar',
);

$this->menu=array(
   array('label'=>'Tambah Data', 'url'=>array('create')),
   array('label'=>'Pencarian Data', 'url'=>array('admin')),
   array('label'=>'Data yang telah dihapus', 'url'=>array('deleted')),
	array('label'=>'Cari LPB dari Jenis Barang', 'url'=>array('finditem')),
	array('label'=>'Cari LPB dari Merk', 'url'=>array('findbrand')),
);
?>

<h1>Penerimaan Barang</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
