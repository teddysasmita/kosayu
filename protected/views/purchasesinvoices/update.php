<?php
/* @var $this PurchasesinvoicesController */
/* @var $model Purchasesinvoices */

$this->breadcrumbs=array(
	'Purchasesinvoices'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
    /*
	array('label'=>'List Purchasesinvoices', 'url'=>array('index')),
	array('label'=>'Create Purchasesinvoices', 'url'=>array('create')),
	array('label'=>'View Purchasesinvoices', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Purchasesinvoices', 'url'=>array('admin')),
    */
     array('label'=>'Add Detail', 'url'=>array('detailpurchasesinvoices/create', 'id'=>$model->id, 
      'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')),    
);
?>

<h1>Update Purchasesinvoices <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>