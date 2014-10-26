<?php
/* @var $this SellingpricesController */
/* @var $model Sellingprices */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Pencarian Harga',
);

$this->menu=array(
	//array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sellingprices-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Penentuan Harga Jual</h1>

<div class="form">

<?php 
	echo CHtml::beginForm(Yii::app()->createUrl('sellingprice/default/displaysellingprice'), 'get');
?>
	
<div class="row">
<?php 
	echo CHtml::label('Nama Barang', 'itemname');
	$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
			'name'=>'itemname',
			'sourceUrl'=>Yii::app()->createUrl('LookUp/getItemName'),
			'htmlOptions'=>array('size'=>50),
	));
?>
</div>
<div class="row">
<?php 
	echo CHtml::submitButton('Cari');
?>
</div>

<?php 
	echo CHtml::endForm();
?>
</div>

