<?php
/* @var $this ItemtipgroupsController */
/* @var $model Itemtipgroups */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Sejarah',
);

$this->menu=array(
	array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Kelompok Komisi Barang</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('itemtipgroups')->where('id=:id',array(':id'=>$model->id))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'itemtipgroups-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'name',
		'pct',
		/*
		'discount',
		'status',
		'remark',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryItemtipGroupUrl(\$data)",
		),
	),
)); ?>
