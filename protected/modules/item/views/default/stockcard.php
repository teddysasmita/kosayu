<?php
/* @var $this ItemsController */
/* @var $model Items */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Cetak Kartu Stok',
);

$this->menu=array(
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Item Penjualan</h1>

<div class="form">


<?php 
$itemScript=<<<EOS
      $('#idwarehouse').change(
         function(){
            var mainform;
	     	var hiddenvar;
	     	mainform=$('#stockcard-form');
	    	mainform.submit();
	     	event.preventDefault();
         }
      );
EOS;
Yii::app()->clientScript->registerScript('itemscript', $itemScript, CClientScript::POS_READY);
 

	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'stockcard-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php 
		echo CHtml::hiddenField('id', $id);
	?>

	<div class="row">
		<?php echo CHtml::label('Pilih Gudang', false); ?>
		<?php
			$data=CHtml::listData($warehouses, 'id', 'code'); 
			echo CHtml::dropDownList('idwarehouse', false, $data, 
				array('empty'=>'Harap Pilih')); 
		?>
	</div>


<?php $this->endWidget(); ?>

</div><!-- form -->

