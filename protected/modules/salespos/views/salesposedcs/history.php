<?php
/* @var $this SalesposedcsController */
/* @var $model Salesposedcs */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Sejarah',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Mesin EDC</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('salesposedcs')->where('id=:id',array(':id'=>$model->id))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'salesposedcs-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'name',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryCustomerUrl(\$data)",
		),
	),
)); ?>
