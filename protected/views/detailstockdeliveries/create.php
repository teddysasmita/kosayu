<?php
/* @var $this DetailstockdeliveriesController */
/* @var $model Detailstockdeliveries */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
	'Stockdeliveries'=>array('stockdeliveries/index'),
	"Create Stockdeliveries #$model->id"=>array('stockdeliveries/create','id'=>$model->id),
      'Create'); 
else if ($master=='update')
   $this->breadcrumbs=array(
	'Stockdeliveries'=>array('stockdeliveries/index'),
	"Update Stockdeliveries #$model->id"=>array('stockdeliveries/update','id'=>$model->id),
      'Update'); 


$this->menu=array(
	array('label'=>'List Detailstockdeliveries', 'url'=>array('index')),
	array('label'=>'Manage Detailstockdeliveries', 'url'=>array('admin')),
);
?>

<h1>Create Detailstockdeliveries</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Create')); ?>