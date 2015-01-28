<?php
/* @var $this ConsignpaymentsController */
/* @var $model Consignpayments */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	/*array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Pengaturan', 'url'=>array('admin')),
   */
	array('label'=>'Tambah Metode Bayar', 'url'=>array('detailconsignpayments2/create', 
      'idtransaction'=>$model->id),
      'linkOptions'=>array('id'=>'addpayment')),  
);

$jq=<<<EOH
   $('#addpayment').click(function(event){
     var mainform;
     var hiddenvar;
     mainform=$('#consignpayments-form');
     $('#command').val('addpayment');
     mainform.submit();
   });
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Pembayaran Konsinyasi</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>