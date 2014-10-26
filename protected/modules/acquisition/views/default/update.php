<?php
/* @var $this AcquisitionsController */
/* @var $model Acquisitions */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Acquisitions', 'url'=>array('index')),
	//array('label'=>'Create Acquisitions', 'url'=>array('create')),
	//array('label'=>'View Acquisitions', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Acquisitions', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailacquisitions/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Akuisisi Barang dan Nomor Seri</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>