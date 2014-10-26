<?php
/* @var $this DetailpurchasesinvoicesController */
/* @var $model Detailpurchasesinvoices */

$this->breadcrumbs=array(
	'View Purchasesinvoices '.$model->id=>array('purchasesinvoices/view', 'id'=>$model->id),
      'Update Purchasesinvoices '.$model->id=>array('purchasesinvoices/update', 'id'=>$model->id),
	'Update',
);


$this->menu=array(
	array('label'=>'List Detailpurchasesinvoices', 'url'=>array('index')),
	array('label'=>'Create Detailpurchasesinvoices', 'url'=>array('create')),
	array('label'=>'View Detailpurchasesinvoices', 'url'=>array('view', 'id'=>$model->iddetail)),
	array('label'=>'Manage Detailpurchasesinvoices', 'url'=>array('admin')),
);
?>

<h1>Update Detailpurchasesinvoices <?php echo $model->iddetail; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>