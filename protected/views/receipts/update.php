<?php
/* @var $this ReceiptsController */
/* @var $model Receipts */

$this->breadcrumbs=array(
	'Receipts'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	/*array('label'=>'List Receipts', 'url'=>array('index')),
	array('label'=>'Create Receipts', 'url'=>array('create')),
	array('label'=>'View Receipts', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Receipts', 'url'=>array('admin')),*/
      array('label'=>'Add Detail', 'url'=>array('detailreceipts/create', 'id'=>$model->id, 
      'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')),
);
?>

<h1>Update Receipts <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>