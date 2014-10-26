<?php
/* @var $this PurchasesinvoicesController */
/* @var $model Purchasesinvoices */

$this->breadcrumbs=array(
	'Purchasesinvoices'=>array('index'),
	'Create',
);

$this->menu=array(
	/*array('label'=>'List Purchasesinvoices', 'url'=>array('index')),
	array('label'=>'Manage Purchasesinvoices', 'url'=>array('admin')),
    */
    array('label'=>'Add Detail', 'url'=>array('detailpurchasesinvoices/create', 'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')),  
);

$jq=<<<EOH
$(function(){
  $('#adddetail').click(function(event){
        var mainform;
        var hiddenvar;
        mainform=$('#purchasesinvoices-form');
        $('#command').val('adddetail');
        mainform.submit();
        event.preventDefault();
  });
}); 
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);

?>

<h1>Create Purchasesinvoices</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>