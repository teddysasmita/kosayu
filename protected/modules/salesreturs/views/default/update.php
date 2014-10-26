<?php
/* @var $this SalesretursController */
/* @var $model Salesreturs */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Salesreturs', 'url'=>array('index')),
	//array('label'=>'Create Salesreturs', 'url'=>array('create')),
	//array('label'=>'View Salesreturs', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Salesreturs', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailsalesreturs/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
   array('label'=>'Tambah Detil2', 'url'=>array('detailsalesreturs2/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail2')) 
);
?>

<h1>Retur Penjualan</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>