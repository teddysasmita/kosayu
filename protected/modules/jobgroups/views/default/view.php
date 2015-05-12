<?php
/* @var $this JobgroupsController */
/* @var $model Jobgroups */

$this->breadcrumbs=array(
   'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
	//array('label'=>'Cetak Kartu Stok', 'url'=>array('printstockcard', 'id'=>$model->id)),
	//array('label'=>'Cetak Kartu Stok Kosong', 'url'=>array('printblankstockcard', 'id'=>$model->id)),
	array('label'=>'Penjualan', 'url'=>array('showSales', 'id'=>$model->id)),
);
?>

<h1>Jabatan</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		array(
			'name'=>'wage',
			'value'=>lookup::getWageStat($model->wage),		
		),
		array(
			'name'=>'thr',
			'value'=>lookup::getYesNo($model->thr),
		),
		array(
			'name'=>'bonus',
			'value'=>lookup::getYesNo($model->bonus),
		),
		array(
			'name'=>'cashier',
			'value'=>lookup::getYesNo($model->cashier),
		),
		array(
			'name'=>'userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog)
        ),
		'datetimelog',
	),
)); ?>
