<?php
/* @var $this Detailpurchasesorders2Controller */
/* @var $model Detailpurchasesorders2 */

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
	//array('label'=>'List Detailpurchasesorders2', 'url'=>array('index')),
	//array('label'=>'Create Detailpurchasesorders2', 'url'=>array('create')),
	//array('label'=>'View Detailpurchasesorders2', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailpurchasesorders2', 'url'=>array('admin')), 
);
?>

<h1>Pemesanan ke Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>