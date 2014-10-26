<?php
/* @var $this AcquisitionsnsnController */
/* @var $model Acquisitionsnsn */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
	array('label'=>'Data Detil yang dihapus', 
         'url'=>array('/purchasesorder/detailacquisitionsnsn/deleted', 'id'=>$model->id)),
	array('label'=>'Ringkasan', 'url'=>array('summary', 'id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('printsummary', 'id'=>$model->id)),
);
?>

<h1>Akuisisi Barang dan Nomor Seri</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
			'name'=>'iditem',
			'value'=>lookup::ItemNameFromItemID($model->iditem)
		),
		array(
			'name'=>'idwarehouse',
			'value'=>lookup::WarehouseNameFromWarehouseID($model->idwarehouse)
		),
		array(
			'name'=>'userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog),
		),
		'datetimelog',
	),
)); ?>

