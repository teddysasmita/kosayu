<?php
/* @var $this PurchasesmemosController */
/* @var $model Purchasesmemos */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Purchasesmemos', 'url'=>array('index')),
	array('label'=>'Create Purchasesmemos', 'url'=>array('create')),
	array('label'=>'View Purchasesmemos', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Purchasesmemos', 'url'=>array('admin')),
   array('label'=>'Tambah Detil', 'url'=>array('detailstockentries/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
      'linkOptions'=>array('id'=>'adddetail')), 
    */
);
?>

<h1>Memo Pembelian</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>