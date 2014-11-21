<?php
/* @var $this DetailpartnersController */
/* @var $model Detailpartners */

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
	//array('label'=>'List Detailpartners', 'url'=>array('index')),
	//array('label'=>'Create Detailpartners', 'url'=>array('create')),
	//array('label'=>'View Detailpartners', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailpartners', 'url'=>array('admin')), 
);
?>

<h1>Rekanan / Agen</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>