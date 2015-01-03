<?php
/* @var $this ItembatchController */
/* @var $model Itembatch */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
	'Sejarah',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Kode Batch Barang</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('itembatch')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'itembatch-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'batchcode',
		'iditem',
		'buyprice',
		'baseprice',
		/*
		'objects',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryItemBatchUrl(\$data)",
		),
	),
)); ?>
