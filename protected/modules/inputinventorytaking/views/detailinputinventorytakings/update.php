<?php
/* @var $this DetailinputinventorytakingsController */
/* @var $model Detailinputinventorytakings */

$master=Yii::app()->session['master'];
if($master=='create') {
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Tambah Data'=>array('default/create','id'=>$model->id),
      'Ubah Detil'); 
} else if ($master=='update') {
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Lihat Data'=>array('default/view', 'id'=>$model->id),
      'Ubah Data'=>array('default/update', 'id'=>$model->id),
      'Ubah Detil',
   );
}   

$this->menu=array(
	/*
   array('label'=>'List Detailinputinventorytakings', 'url'=>array('index')),
	array('label'=>'Create Detailinputinventorytakings', 'url'=>array('create')),
	array('label'=>'View Detailinputinventorytakings', 'url'=>array('view', 'iddetail'=>$model->iddetail)),
	array('label'=>'Manage Detailinputinventorytakings', 'url'=>array('admin')),
   */
 );
?>

<h1>Input Stok Opname</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>