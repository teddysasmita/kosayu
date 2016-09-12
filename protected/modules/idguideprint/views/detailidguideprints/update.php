<?php
/* @var $this DetailidguideprintsController */
/* @var $model Detailidguideprints */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Tambah Data'=>array('default/create'),
      'Ubah Detil'); 
else if ($master=='update')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Lihat Data'=>array('default/view','id'=>$model->id),
      'Ubah Data'=>array('default/update','id'=>$model->id),
      'Ubah Detil');

$this->menu=array(
	//array('label'=>'List Detailidguideprints', 'url'=>array('index')),
	//array('label'=>'Create Detailidguideprints', 'url'=>array('create')),
	//array('label'=>'View Detailidguideprints', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailidguideprints', 'url'=>array('admin')), 
);
?>

<h1>Detil Cetak Kartu Guide</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>