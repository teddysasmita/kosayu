<?php
/* @var $this SalesinvoicesController */
/* @var $model Salesinvoices */

$this->breadcrumbs=array(
	'Salesinvoices'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Salesinvoices', 'url'=>array('index')),
	array('label'=>'Manage Salesinvoices', 'url'=>array('admin')),
    array('label'=>'Add Detail', 'url'=>array('detailsalesinvoices/create', 'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')),
);
$jq=<<<EOH
$(function(){
  $('#adddetail').click(function(event){
        var mainform;
        mainform=$('#salesinvoices-form');
        $('#command').val('adddetail');
        mainform.submit();
        event.preventDefault();
  });
   $('#idcustomer').change(function(event){
        var mainform;
        mainform=$('#salesinvoices-form');
        $('#command').val('setidcustomer');
        mainform.submit();
        event.preventDefault();
   });
    $('#idinvoice').change(function(event){
        var mainform;
        mainform=$('#salesinvoices-form');
        $('#command').val('setidinvoice');
        mainform.submit();
        event.preventDefault();
   });
}); 
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Create Salesinvoices</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create') ); ?>