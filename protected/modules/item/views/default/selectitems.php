<?php
/* @var $this ItemsController */
/* @var $model Items */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Export ke Excel',
);

$this->menu=array(
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Item Penjualan</h1>


<?php 
$namescript=<<<OK
   function combine(separator, brand, object, model, attribute) {
      return object + separator + brand + separator + model + separator + attribute;
   };

     $('#Items_brand').change(function(event) {
         $('#Items_name').val(combine( ' ', $('#Items_brand').val(), $('#Items_objects').val(), $('#Items_model').val(), $('#Items_attribute').val() ));
     });
     $('#Items_objects').change(function(event) {
        $('#Items_name').val(combine( ' ', $('#Items_brand').val(), $('#Items_objects').val(), $('#Items_model').val(), $('#Items_attribute').val() ));
     });
     $('#Items_model').change(function(event) {
        $('#Items_name').val(combine( ' ', $('#Items_brand').val(), $('#Items_objects').val(), $('#Items_model').val(), $('#Items_attribute').val() ));
     });
     $('#Items_attribute').change(function(event) {
        $('#Items_name').val(combine( ' ', $('#Items_brand').val(), $('#Items_objects').val(), $('#Items_model').val(), $('#Items_attribute').val() ));
     });
OK;
Yii::app()->clientScript->registerScript('myscript', $namescript, CClientScript::POS_READY);

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
 

?>

<div class='form'>

	<?php 
		echo CHtml::form(Yii::app()->createUrl('item/default/export2xcl'), 'Post');
	?>

	<div class="row">
		<?php echo CHtml::label('Jenis Barang', FALSE); ?>
		<?php
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'selectitems[object]',
				'sourceUrl'=> Yii::app()->createUrl('LookUp/getObjects'),
				'htmlOptions'=>array(
				'style'=>'height:20px;',
				),
			));
		?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Merk/Brand', FALSE); ?>
		<?php
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'selectitems[brand]',
                'sourceUrl'=> Yii::app()->createUrl('LookUp/getBrand'),
                'htmlOptions'=>array(
					'style'=>'height:20px;',
				),
			));
		?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Model/Tipe', FALSE); ?>
		<?php
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'selectitems[model]',
                'sourceUrl'=> Yii::app()->createUrl('LookUp/getModel'),
                'htmlOptions'=>array(
					'style'=>'height:20px;',
				),
			));
		?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Export'); ?>
	</div>

	<?php CHtml::endForm(); ?>

</div>


