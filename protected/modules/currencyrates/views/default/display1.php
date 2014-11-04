<?php
/* @var $this CurrencyratesController */
/* @var $model Currencyrates */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Pencarian Nilai Tukar',
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
	$('#currencyrates-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Penentuan Nilai Tukar Mata Uang Asing</h1>

<div class="form">

<?php 
	echo CHtml::beginForm(Yii::app()->createUrl('currencyrate/default/displaycurrencyrate'), 'get');
?>
	
<div class="row">
<?php 
	echo CHtml::label('Nama Mata Uang Asing', 'currname');
	$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
			'name'=>'currname',
			'sourceUrl'=>Yii::app()->createUrl('LookUp/getCurrName'),
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

