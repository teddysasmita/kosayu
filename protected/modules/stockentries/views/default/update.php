<?php
/* @var $this StockentriesController */
/* @var $model Stockentries */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Stockentries', 'url'=>array('index')),
	//array('label'=>'Create Stockentries', 'url'=>array('create')),
	//array('label'=>'View Stockentries', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Stockentries', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailstockentries/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Penerimaan Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>