<?php
/* @var $this Detailpurchasesorders2Controller */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailpurchasesorders2s',
);

$this->menu=array(
	array('label'=>'Create Detailpurchasesorders2', 'url'=>array('create')),
	array('label'=>'Manage Detailpurchasesorders2', 'url'=>array('admin')),
);
?>

<h1>Detailpurchasesorders2s</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
