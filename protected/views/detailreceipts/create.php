<?php
/* @var $this DetailreceiptsController */
/* @var $model Detailreceipts */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
	'Receipts'=>array('receipts/index'),
	"Create Receipts #$model->id"=>array('receipts/create','id'=>$model->id),
      'Create'); 
else if ($master=='update')
   $this->breadcrumbs=array(
	'Receipts'=>array('salesorders/index'),
	"Update Receipts #$model->id"=>array('salesorders/update','id'=>$model->id),
      'Update'); 


$this->menu=array(
	array('label'=>'List Detailreceipts', 'url'=>array('index')),
	array('label'=>'Manage Detailreceipts', 'url'=>array('admin')),
);
?>

<h1>Create Detailreceipts</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Create')); ?>