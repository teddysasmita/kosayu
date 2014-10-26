<?php
/* @var $this SalesinvoicesController */
/* @var $model Salesinvoices */

$this->breadcrumbs=array(
	'Salesinvoices'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	/*array('label'=>'List Salesinvoices', 'url'=>array('index')),
	array('label'=>'Create Salesinvoices', 'url'=>array('create')),
	array('label'=>'View Salesinvoices', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Salesinvoices', 'url'=>array('admin')),*/
    array('label'=>'Add Detail', 'url'=>array('detailsalesinvoices/create', 'id'=>$model->id, 
      'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')),     
);
?>

<h1>Update Salesinvoices <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>