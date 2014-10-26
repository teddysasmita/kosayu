<?php
/* @var $this OrderretrievalsController */
/* @var $model Orderretrievals */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Orderretrievals', 'url'=>array('index')),
	//array('label'=>'Create Orderretrievals', 'url'=>array('create')),
	//array('label'=>'View Orderretrievals', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Orderretrievals', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailorderretrievals/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
   array('label'=>'Tambah Detil2', 'url'=>array('detailorderretrievals2/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail2')) 
);
?>

<h1>Pengambilan Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update', 'form_error'=>$form_error)); ?>