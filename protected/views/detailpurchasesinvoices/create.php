<?php
/* @var $this DetailpurchasesinvoicesController */
/* @var $model Detailpurchasesinvoices */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
	'Purchasesinvoices'=>array('purchasesinvoices/index'),
	"Create Purchaseinvoices #$model->id"=>array('purchasesinvoices/create','id'=>$model->id),
      'Create'); 
else if ($master=='update')
   $this->breadcrumbs=array(
	'Purchasesinvoices'=>array('purchasesinvoices/index'),
	"Update Purchasesinvoices #$model->id"=>array('purchasesinvoices/update','id'=>$model->id),
      'Update'); 

$this->menu=array(
	array('label'=>'List Detailpurchasesinvoices', 'url'=>array('index')),
	array('label'=>'Manage Detailpurchasesinvoices', 'url'=>array('admin')),
);
?>

<h1>Create Detailpurchasesinvoices</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Create')); ?>