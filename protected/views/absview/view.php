<?php
/* @var $this SalesordersController */
/* @var $model Salesorders */

$this->breadcrumbs=array(
	'Salesorders'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Salesorders', 'url'=>array('index')),
	array('label'=>'Create Salesorders', 'url'=>array('create')),
	array('label'=>'Update Salesorders', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Salesorders', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Salesorders', 'url'=>array('admin')),
);
?>

<h1>View Salesorders #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idatetime',
            array(
                'label'=>'Pelanggan',
                'value'=>lookup::CustomerNameFromCustomerID($model->idcustomer)
            ),
		array(
                'label'=>'Total',
                'type'=>'number',
                'name'=>'total'
            ),
            array(
                'label'=>'Diskon',
                'type'=>'number',
                'name'=>'discount'
            ),
		'status',
	),
)); ?>

<?php 
$count=Yii::app()->db->createCommand("select count(*) from detailsalesorders where id='$model->id'")->queryScalar();
$sql="select * from detailsalesorders where id='$model->id'";

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
          array(
              'header'=>'Price',
              'name'=>'price',
              'type'=>'number',
          ),
          array(
              'header'=>'Disc',
              'name'=>'discount',
              'type'=>'number'
          ),
      ),
));
 ?>

