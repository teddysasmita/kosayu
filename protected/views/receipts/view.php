<?php
/* @var $this ReceiptsController */
/* @var $model Receipts */

$this->breadcrumbs=array(
	'Receipts'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Receipts', 'url'=>array('index')),
	array('label'=>'Create Receipts', 'url'=>array('create')),
	array('label'=>'Update Receipts', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Receipts', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Receipts', 'url'=>array('admin')),
);
?>

<h1>View Receipts #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'regnum',
		'idatetime',
		'idcustomer',
		'total',
		'discount',
		'status',
		'userlog',
		'datetimelog',
	),
)); ?>

<?php 
$count=Yii::app()->db->createCommand("select count(*) from detailreceipts where id='$model->id'")->queryScalar();
$sql="select * from detailreceipts where id='$model->id'";

$dataProvider=new CSqlDataProvider($sql,array(
       'totalItemCount'=>$count,
       ));
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
           array(
               'header'=>'Item Name',
               'name'=>'iditem',
               'value'=>"lookup::PurchasesInvoiceNumFromInvoiceID(\$data['idinvoice'])"
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
