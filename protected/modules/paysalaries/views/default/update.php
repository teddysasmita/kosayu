<?php
/* @var $this PaysalariesController */
/* @var $model Paysalaries */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Paysalaries', 'url'=>array('index')),
	//array('label'=>'Create Paysalaries', 'url'=>array('create')),
	//array('label'=>'View Paysalaries', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Paysalaries', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailpaysalaries/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
   array('label'=>'Tambah Detil2', 'url'=>array('detailpaysalaries2/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail2')) 
);
?>

<h1>Pembayaran Gaji Karyawan</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>