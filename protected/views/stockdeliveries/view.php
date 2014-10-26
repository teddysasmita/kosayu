<?php
/* @var $this StockdeliveriesController */
/* @var $model Stockdeliveries */

$this->breadcrumbs=array(
	'Stockdeliveries'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Stockdeliveries', 'url'=>array('index')),
	array('label'=>'Create Stockdeliveries', 'url'=>array('create')),
	array('label'=>'Update Stockdeliveries', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Stockdeliveries', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Stockdeliveries', 'url'=>array('admin')),
);
?>

<h1>View Stockdeliveries #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idatetime',
            array(
                'label'=>'Pelanggan',
                'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
            ),
	),
)); ?>

<?php 
$count=Yii::app()->db->createCommand("select count(*) from detailstockdeliveries where id='$model->id'")->queryScalar();
$sql="select * from detailstockdeliveries where id='$model->id'";

$dataProvider=new CSqlDataProvider($sql,array(
       'totalItemCount'=>$count,
       ));
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
           array(
               'header'=>'Item Name',
               'name'=>'iditem',
               'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
           ),
          array(
              'header'=>'Qty',
              'name'=>'qty',
          ),   
      ),
));
 ?>
