<?php
/* @var $this InputinventorytakingsController */
/* @var $model Inputinventorytakings */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Pencarian Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#inputinventorytakings-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Input Stok Opname</h1>

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
	'id'=>'inputinventorytakings-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'idatetime',
		'regnum',
		'idinventorytaking',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
