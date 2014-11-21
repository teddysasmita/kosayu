<?php
/* @var $this TippaymentsController */
/* @var $model Tippayments */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	/* 
	array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Pengaturan', 'url'=>array('admin')),
      array('label'=>'Tambah Detil', 'url'=>array('detailtippayments/create', 
         'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')), 
	*/
);

$jq=<<<EOH
   $('#adddetail').click(function(event){
     var mainform;
     var hiddenvar;
     mainform=$('#tippayments-form');
     $('#command').val('adddetail');
     mainform.submit();
     event.preventDefault();
   });
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Pembayaran Komisi Agen</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'compositions'=>$compositions, 
		'id'=>$model->id, 'command'=>'create')); ?>