<?php
/* @var $this BarcodeprintsController */
/* @var $model Barcodeprints */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Barcodeprints', 'url'=>array('index')),
	//array('label'=>'Create Barcodeprints', 'url'=>array('create')),
	//array('label'=>'View Barcodeprints', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Barcodeprints', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailbarcodeprints/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Cetak Barcode</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>