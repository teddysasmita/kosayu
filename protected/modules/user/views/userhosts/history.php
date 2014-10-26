<?php
/* @var $this UserhostsController */
/* @var $model Userhosts */

$this->breadcrumbs=array(
	'Userhosts'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Userhosts', 'url'=>array('index')),
	array('label'=>'Create Userhosts', 'url'=>array('create')),
);

?>

<h1>History Userhosts</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('userhosts')->where('id=:id',array(':id'=>$model->id))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'userhosts-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'iduser',
		'allowedip',
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
