<?php
/* @var $this SalesordersController */
/* @var $model Salesorders */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Salesorders', 'url'=>array('index')),
	//array('label'=>'Create Salesorders', 'url'=>array('create')),
	//array('label'=>'View Salesorders', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Salesorders', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailsalesorders/create', 'id'=>$model->id, 
      'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')),     
);
?>

<h1>Pemesanan oleh Pelanggan</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>