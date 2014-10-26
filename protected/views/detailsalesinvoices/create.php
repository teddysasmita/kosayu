<?php
/* @var $this DetailsalesinvoicesController */
/* @var $model Detailsalesinvoices */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
	'Salesinvoices'=>array('salesinvoices/index'),
	"Create Salesinvoices #$model->id"=>array('salesinvoices/create','id'=>$model->id),
      'Create'); 
else if ($master=='update')
   $this->breadcrumbs=array(
	'Salesinvoices'=>array('salesinvoices/index'),
	"Update Salesinvoices #$model->id"=>array('salesinvoices/update','id'=>$model->id),
      'Update'); 
  
$this->menu=array(
	array('label'=>'List Detailsalesinvoices', 'url'=>array('index')),
	array('label'=>'Manage Detailsalesinvoices', 'url'=>array('admin')),
);
?>

<h1>Create Detailsalesinvoices</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Create')); ?>