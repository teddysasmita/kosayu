<?php
/* @var $this Detailreturstocks2Controller */
/* @var $model Detailreturstocks2 */

$this->breadcrumbs=array(
	'Detailreturstocks2'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Detailreturstocks2', 'url'=>array('index')),
	array('label'=>'Create Detailreturstocks2', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#detailreturstocks2-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Pengembalian Barang ke Pemasok</h1>

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
	'id'=>'detailreturstocks2-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'iddetail',
		'id',
		'iditem',
		'idunit',
		'qty',
		'discount',
		/*
		'price',
		'userlog',
		'datetimelog',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
