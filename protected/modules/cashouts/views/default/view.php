<?php
/* @var $this CashoutsController */
/* @var $model Cashouts */

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
);
?>

<h1>Pencatatan Kas Keluar</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'regnum',
		'idatetime',
		array(
			'name'=>'idexpense',
			'value'=>lookup::ExpenseNameFromID($model['idexpense']),
		),
		array(
			'name'=>'idacctcredit',
			'value'=>lookup::CashboxNameFromID($model['idacctcredit']),
		),
		array(
			'name'=>'amount',
			'value'=>number_format($model['amount'])
		),
		array(
			'name'=>'userlog',
			'value'=>lookup::UserNameFromUserID($model['userlog'])
		),
		'datetimelog',
	),
)); ?>
