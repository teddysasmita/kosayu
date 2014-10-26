<?php
/* @var $this ItemsController */
/* @var $model Items */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
	'Sejarah',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Item Penjualan</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('items')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'items-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'code',
		'type',
		'name',
		'brand',
		'objects',
		/*
		'model',
		'attribute',
		'picture',
		'rowdeleted',
		'userlog',
		'datetimelog',
		*/
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryItemUrl(\$data)",
		),
	),
)); ?>
