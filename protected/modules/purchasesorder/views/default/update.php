<?php
/* @var $this PurchasesordersController */
/* @var $model Purchasesorders */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Purchasesorders', 'url'=>array('index')),
	//array('label'=>'Create Purchasesorders', 'url'=>array('create')),
	//array('label'=>'View Purchasesorders', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Purchasesorders', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailpurchasesorders/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
   array('label'=>'Tambah Detil2', 'url'=>array('detailpurchasesorders2/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail2')) 
);
?>

<h1>Pemesanan ke Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>