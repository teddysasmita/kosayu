<?php
/* @var $this TippaymentsController */
/* @var $model Tippayments */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Tippayments', 'url'=>array('index')),
	//array('label'=>'Create Tippayments', 'url'=>array('create')),
	//array('label'=>'View Tippayments', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Tippayments', 'url'=>array('admin')),
);
?>

<h1>Pembayaran Komisi Agen</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>