<?php
/* @var $this AcquisitionsnsnController */
/* @var $model Acquisitionsnsn */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Acquisitionsnsn', 'url'=>array('index')),
	//array('label'=>'Create Acquisitionsnsn', 'url'=>array('create')),
	//array('label'=>'View Acquisitionsnsn', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Acquisitionsnsn', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailacquisitionsnsn/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Akuisisi Barang TANPA Nomor Seri</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>