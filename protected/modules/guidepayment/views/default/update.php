<?php
/* @var $this GuidepaymentsController */
/* @var $model Guidepayments */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Guidepayments', 'url'=>array('index')),
	//array('label'=>'Create Guidepayments', 'url'=>array('create')),
	//array('label'=>'View Guidepayments', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Guidepayments', 'url'=>array('admin')),
);
?>

<h1>Pembayaran Komisi Guide</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>