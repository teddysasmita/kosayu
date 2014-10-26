<?php
/* @var $this DetailinputinventorytakingsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Pencarian Data'
); 

$this->menu=array(
	/*array('label'=>'Create Detailinputinventorytakings', 'url'=>array('create')),
	array('label'=>'Manage Detailinputinventorytakings', 'url'=>array('admin')),
	*/
);
?>

<h1>Input Stok Opname</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
