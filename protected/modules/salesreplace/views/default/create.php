<?php
/* @var $this SalesreplaceController */
/* @var $model Salesreplace */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'Daftar Perubahan Penjualan', 'url'=>array('index')),
	//array('label'=>'Cari Perubahan Penjualan', 'url'=>array('admin')),
);

$jq=<<<EOH
   $('#adddetail').click(function(event){
     var mainform;
     var hiddenvar;
     mainform=$('#purchasesorders-form');
     $('#command').val('adddetail');
     mainform.submit();
     event.preventDefault();
   });
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Ganti Barang Penjualan</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'command'=>'create')); ?>