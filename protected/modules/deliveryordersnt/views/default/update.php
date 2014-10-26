<?php
/* @var $this DeliveryordersntController */
/* @var $model Deliveryordersnt */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Deliveryordersnt', 'url'=>array('index')),
	//array('label'=>'Create Deliveryordersnt', 'url'=>array('create')),
	//array('label'=>'View Deliveryordersnt', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Deliveryordersnt', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detaildeliveryordersnt/create', 'id'=>$model->id, 
      'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')),     
);
?>

<h1>Pengiriman Barang Tanpa Transaksi</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>