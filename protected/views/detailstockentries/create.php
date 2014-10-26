<?php
/* @var $this DetailstockentriesController */
/* @var $model Detailstockentries */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
	'Stockentries'=>array('stockentries/index'),
	"Create Stockentries #$model->id"=>array('stockentries/create','id'=>$model->id),
      'Create'); 
else if ($master=='update')
   $this->breadcrumbs=array(
	'Stockentries'=>array('stockentries/index'),
	"Update Stockentries #$model->id"=>array('stockentries/update','id'=>$model->id),
      'Update'); 


$this->menu=array(
	array('label'=>'List Detailstockentries', 'url'=>array('index')),
	array('label'=>'Manage Detailstockentries', 'url'=>array('admin')),
);
?>

<h1>Create Detailstockentries</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Create')); ?>