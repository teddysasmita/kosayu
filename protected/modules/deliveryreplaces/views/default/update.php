<?php
/* @var $this DeliveryreplacesController */
/* @var $model Deliveryreplaces */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Deliveryreplaces', 'url'=>array('index')),
	//array('label'=>'Create Deliveryreplaces', 'url'=>array('create')),
	//array('label'=>'View Deliveryreplaces', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Deliveryreplaces', 'url'=>array('admin')),
    /*array('label'=>'Tambah Detil', 'url'=>array('detaildeliveryreplaces/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
   array('label'=>'Tambah Detil2', 'url'=>array('detaildeliveryreplaces2/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail2')) */
);
?>

<h1>Penukaran Pengiriman Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update',
	'form_error'=>$form_error)); ?>