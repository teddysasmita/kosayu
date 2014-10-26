<?php
/* @var $this InventorytakingsController */
/* @var $model Inventorytakings */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
      'Sejarah'
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Stok Opname</h1>

<?php    
	$data=Yii::app()->tracker->createCommand()->
       select()->from('inventorytakings')->where('id=:id',array(':id'=>$model->id))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'inventorytakings-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'operationlabel',
		'remark',
		'status',
		'userlog',
		'datetimelog',
		array(
			'class'=>'CButtonColumn',
			'buttons'=> array(
				'view'=>array(
					'visible'=>'false',
				),
				'delete'=>array(
					'visible'=>'false',
				),
			),
			'updateButtonUrl'=>"Action::decodeRestoreHistoryInventorytakingUrl(\$data)",
		),
	),
)); ?>
