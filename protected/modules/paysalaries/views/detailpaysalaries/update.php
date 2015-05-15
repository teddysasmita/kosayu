<?php
/* @var $this DetailpaysalariesController */
/* @var $model Detailpaysalaries */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Tambah Data'=>array('default/create','id'=>$model->id),
      'Ubah Detil'); 
else if ($master=='update')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Lihat Data'=>array('default/view','id'=>$model->id),
      'Ubah Data'=>array('default/update','id'=>$model->id),
      'Ubah Detil');

$this->menu=array(
	//array('label'=>'List Detailpaysalaries', 'url'=>array('index')),
	//array('label'=>'Create Detailpaysalaries', 'url'=>array('create')),
	//array('label'=>'View Detailpaysalaries', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailpaysalaries', 'url'=>array('admin')), 
);
?>

<h1>Detil Pembayaran Gaji Karyawan</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>