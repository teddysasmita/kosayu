<?php
/* @var $this DetailreceiptsController */
/* @var $model Detailreceipts */

$this->breadcrumbs=array(
	'View Receipts '.$model->id=>array('receipts/view', 'id'=>$model->id),
      'Update Receipts '.$model->id=>array('receipts/update', 'id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Detailreceipts', 'url'=>array('index')),
	array('label'=>'Create Detailreceipts', 'url'=>array('create')),
	array('label'=>'View Detailreceipts', 'url'=>array('view', 'id'=>$model->iddetail)),
	array('label'=>'Manage Detailreceipts', 'url'=>array('admin')),
);
?>

<h1>Update Detailreceipts <?php echo $model->iddetail; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>