<?php
/* @var $this DetailpaymentsController */
/* @var $model Detailpayments */

$this->breadcrumbs=array(
      'View Receipts '.$model->id=>array('receipts/view', 'id'=>$model->id),
      'Update Receipts '.$model->id=>array('receipts/update', 'id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Detailpayments', 'url'=>array('index')),
	array('label'=>'Create Detailpayments', 'url'=>array('create')),
	array('label'=>'View Detailpayments', 'url'=>array('view', 'id'=>$model->iddetail)),
	array('label'=>'Manage Detailpayments', 'url'=>array('admin')),
);
?>

<h1>Update Detailpayments <?php echo $model->iddetail; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>