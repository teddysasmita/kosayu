<?php
/* @var $this DeliveryordersController */
/* @var $model Deliveryorders */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Deliveryorders', 'url'=>array('index')),
	//array('label'=>'Create Deliveryorders', 'url'=>array('create')),
	//array('label'=>'View Deliveryorders', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Deliveryorders', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detaildeliveryorders/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
   array('label'=>'Tambah Detil2', 'url'=>array('detaildeliveryorders2/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail2')) 
);
?>

<h1>Pengiriman Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update',
	'form_error'=>$form_error)); ?>