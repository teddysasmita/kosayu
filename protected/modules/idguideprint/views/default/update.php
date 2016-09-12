<?php
/* @var $this IdguideprintsController */
/* @var $model Idguideprints */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Idguideprints', 'url'=>array('index')),
	//array('label'=>'Create Idguideprints', 'url'=>array('create')),
	//array('label'=>'View Idguideprints', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Idguideprints', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailidguideprints/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Cetak Kartu Guide</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>