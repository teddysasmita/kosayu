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

<h1>Deleted Userhosts</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php
    
    $data=Yii::app()->tracker->createCommand()->
       select('a.*')->from('userhosts a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action', array(':action'=>'d'))->queryAll();
    $ap=new CArrayDataProvider($data);
?>
 

<?php
 $this->widget('zii.widgets.grid.CGridView', array(
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
