<?php
/* @var $this DeliveryordersntController */
/* @var $model Deliveryordersnt */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'List Deliveryordersnt', 'url'=>array('index')),
	//array('label'=>'Manage Deliveryordersnt', 'url'=>array('admin')),
      array('label'=>'Tambah Detil', 'url'=>array('detaildeliveryordersnt/create', 'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')),      
);
$jq=<<<EOH
$(function(){
  $('#adddetail').click(function(event){
        var mainform;
        var hiddenvar;
        mainform=$('#deliveryordersnt-form');
        $('#command').val('adddetail');
        mainform.submit();
        event.preventDefault();
  });
}); 
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Pengiriman Barang Tanpa Transaksi</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>