<?php
/* @var $this StockentriesController */
/* @var $model Stockentries */

$this->breadcrumbs=array(
	'Stockentries'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Stockentries', 'url'=>array('index')),
	array('label'=>'Create Stockentries', 'url'=>array('create')),
	array('label'=>'Update Stockentries', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Stockentries', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Stockentries', 'url'=>array('admin')),
);
?>

<h1>View Stockentries #<?php echo $model->id; ?></h1>

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
$count=Yii::app()->db->createCommand("select count(*) from detailstockentries where id='$model->id'")->queryScalar();
$sql="select * from detailstockentries where id='$model->id'";

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
