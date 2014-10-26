<?php
/* @var $this StockdamageController */
/* @var $model Stockdamage */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Stockdamage', 'url'=>array('index')),
	//array('label'=>'Create Stockdamage', 'url'=>array('create')),
	//array('label'=>'View Stockdamage', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Stockdamage', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailstockdamage/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Barang Rusak</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>