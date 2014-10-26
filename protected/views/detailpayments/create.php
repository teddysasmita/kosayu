<?php
/* @var $this DetailpaymentsController */
/* @var $model Detailpayments */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
	'payments'=>array('payments/index'),
	"Create payments #$model->id"=>array('payments/create','id'=>$model->id),
      'Create'); 
else if ($master=='update')
   $this->breadcrumbs=array(
	'payments'=>array('payments/index'),
	"Update payments #$model->id"=>array('payments/update','id'=>$model->id),
      'Update'); 
  

$this->menu=array(
	array('label'=>'List Detailpayments', 'url'=>array('index')),
	array('label'=>'Manage Detailpayments', 'url'=>array('admin')),
);
?>

<h1>Create Detailpayments</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Create')); ?>