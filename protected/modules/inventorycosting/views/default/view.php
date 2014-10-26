<?php
/* @var $this InventorycostingsController */
/* @var $model Inventorycostings */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Cari Data', 'url'=>array('admin')),
        array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
);
?>

<h1>Penentuan Harga Pokok Opname</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
			'name'=>'idinventorytaking',
			'value'=>lookup::InventoryTakingLabelFromID($model->idinventorytaking),
		),
		array(
			'name'=>'iditem',
			'value'=>lookup::ItemNameFromItemID($model->iditem),
		),
		array(
			'name'=>'cost',
			'type'=>'number',
		),
		array(
			'name'=>'userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog),
		),	
		'datetimelog',
	),
)); ?>
