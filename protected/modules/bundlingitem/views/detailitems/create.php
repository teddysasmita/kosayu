<?php
/* @var $this DetailitemsController */
/* @var $model Detailitems */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
	'Items'=>array('default/index'),
	"Create Items #$model->id"=>array('detailitems/create','id'=>$model->id),
      'Create'); 
else if ($master=='update')
   $this->breadcrumbs=array(
	'Items'=>array('default/index'),
	"Update Items #$model->id"=>array('detailitems/update','id'=>$model->id),
      'Update'); 

$this->menu=array(
	array('label'=>'List Detailitems', 'url'=>array('index')),
	array('label'=>'Manage Detailitems', 'url'=>array('admin')),
);
?>

<h1>Create Detailitems</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Create')); ?>