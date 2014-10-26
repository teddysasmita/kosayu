<?php
/* @var $this InventorycostingsController */
/* @var $model Inventorycostings */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Pencarian Data',
);

$this->menu=array(
	//array('label'=>'List Inventorycostings', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#inventorycostings-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Penentuan Harga Pokok Opname</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'inventorycostings-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
			'name'=>'idinventorytaking',
			'value'=>"lookup::InventoryTakingLabelFromID(\$data['idinventorytaking'])",
		),
		array(
			'name'=>'iditem',
			'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])",
		),
		'cost',
		'userlog',
		'datetimelog',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
