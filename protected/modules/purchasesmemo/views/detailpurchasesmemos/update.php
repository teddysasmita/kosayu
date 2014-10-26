<?php
/* @var $this DetailpurchasesmemosController */
/* @var $model Detailpurchasesmemos */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Tambah Data'=>array('default/create'),
      'Ubah Detil'); 
else if ($master=='update')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Lihat Data'=>array('view','id'=>$model->id),
      'Ubah Data'=>array('default/update','id'=>$model->id),
      'Ubah Detil');

$this->menu=array(
	//array('label'=>'List Detailpurchasesmemos', 'url'=>array('index')),
	//array('label'=>'Create Detailpurchasesmemos', 'url'=>array('create')),
	//array('label'=>'View Detailpurchasesmemos', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailpurchasesmemos', 'url'=>array('admin')), 
);
?>

<h1>Detil Memo Pembelian</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>