<?php
/* @var $this GuidesController */
/* @var $model Guides */

$this->breadcrumbs=array(
   'Master Data'=>array('/site/masterdata'),
   'Daftar'=>array('index'),
   'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Cari Data', 'url'=>array('admin')),
        array('label'=>'History', 'url'=>array('history', 'id'=>$model->id)),
	array('label'=>'Aktivitas', 'url'=>array('viewActivity', 'id'=>$model->id, 'startdate'=>idmaker::getDateTime(),
		'enddate'=>idmaker::getDateTime())),
		array('label'=>'Pembayaran', 'url'=>array('viewPayment', 'id'=>$model->id, 'startdate'=>idmaker::getDateTime(),
				'enddate'=>idmaker::getDateTime())),
);
?>

<h1>Pemandu / Guide</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'firstname',
		'lastname',
		'address',
		'phone',
		'email',
		'idnum',
		[
			'label'=>'Besar Komisi',
			'value'=>lookup::getCommission($model->idpartner, $model->idcomp),	
		],
		[
			'name'=>'userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog),
		],
		'datetimelog'
	),
)); ?>
