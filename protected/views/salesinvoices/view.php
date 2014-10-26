<?php
/* @var $this SalesinvoicesController */
/* @var $model Salesinvoices */

$this->breadcrumbs=array(
	'Salesinvoices'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Salesinvoices', 'url'=>array('index')),
	array('label'=>'Create Salesinvoices', 'url'=>array('create')),
	array('label'=>'Update Salesinvoices', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Salesinvoices', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Salesinvoices', 'url'=>array('admin')),
);
?>

<h1>View Salesinvoices #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idatetime',
            array(
                'label'=>'Pelanggan',
                'value'=>lookup::SupplierNameFromSupplierID($model->idcustomer)
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
$count=Yii::app()->db->createCommand("select count(*) from detailsalesinvoices where id='$model->id'")->queryScalar();
$sql="select * from detailsalesinvoices where id='$model->id'";

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
