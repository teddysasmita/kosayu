<?php
/* @var $this Detailpurchasesorders2Controller */
/* @var $model Detailpurchasesorders2 */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Tambah Data'=>array('default/create','id'=>$model->id),
      'Tambah Detil'); 
else if ($master=='update')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Ubah Data'=>array('default/update','id'=>$model->id),
      'Daftar'=>array('default/index'),
      'Tambah Detil');

/*$this->menu=array(
	array('label'=>'List Detailpurchasesorders2', 'url'=>array('index')),
	array('label'=>'Manage Detailpurchasesorders2', 'url'=>array('admin')),
);*/
?>

<h1>Detil Voucher Pemesanan ke Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Create')); ?>