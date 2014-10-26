<?php
/* @var $this ServicecentersController */
/* @var $model Servicecenters */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
   'Data-data yang telah terhapus'
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);
?>

<h1>Service Center</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php
    
    $data=Yii::app()->tracker->createCommand()->select('a.*')->from('servicecenters a')->
       join('userjournal b', 'b.id=a.idtrack')->where('b.action=:action', array(':action'=>'d'))->queryAll();
    $ap=new CArrayDataProvider($data);
?>

<?php
 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'servicecenters-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'brandname',
		'companyname',
		'address',
		'phone',
		'email',
		/*
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
                   'updateButtonUrl'=>"Action::decodeRestoreDeletedServicecenterUrl(\$data)",
		),
	),
)); ?>
