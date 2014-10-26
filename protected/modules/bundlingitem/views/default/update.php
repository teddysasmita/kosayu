<?php
/* @var $this ItemsController */
/* @var $model Items */

$this->breadcrumbs=array(
	'Items'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

if ($model->type==2) {
   $this->menu=array(
         /*
         array('label'=>'List Items', 'url'=>array('index')),
         array('label'=>'Create Items', 'url'=>array('create')),
         array('label'=>'View Items', 'url'=>array('view', 'id'=>$model->id)),
         array('label'=>'Manage Items', 'url'=>array('admin')),
         */
     array('label'=>'Add Detail', 'url'=>array('detailitems/create', 'id'=>$model->id, 
         'command'=>'update'),
             'linkOptions'=>array('id'=>'adddetail')),
   );
};
?>

<h1>Update Items <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>