<?php
/* @var $this InventorytakingsController */
/* @var $model Inventorytakings */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
    'Lihat Data'
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
	array('label'=>'Data-data yang telah terhapus', 'url'=>array('deleted', 'id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('printsummary', 'id'=>$model->id)),
	array('label'=>'Cetak Kartu Stok', 'url'=>array('stockCard', 'id'=>$model->id)),
	array('label'=>'Cetak Semua Kartu Stok', 'url'=>array('printallstockCard', 'id'=>$model->id)),
);
?>

<h1>Stok Opname</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'operationlabel',
		array(
			'name'=>'status',
			'value'=>lookup::activeStatus($model->status)
		),
		'remark',	
		array(
			'label'=>'userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog),
		),
		'datetimelog',
	),
)); ?>
