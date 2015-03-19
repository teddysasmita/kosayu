<?php
/* @var $this CashoutsController */
/* @var $model Cashouts */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
	array('label'=>'Penyesuaian', 'url'=>'#',
		'linkOptions'=>array('id'=>'adjustButton') ),
);

$cashoutScript=<<<EOS
	$('#adjustButton').click(
		function() {
			$('#AdjustDialog').dialog('open');
		}
	);
EOS;
Yii::app()->clientScript->registerScript('cashoutscript', $cashoutScript, CClientScript::POS_READY);

?>

<h1>Pencatatan Kas Keluar</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'regnum',
		'idatetime',
		array(
			'name'=>'idexpense',
			'value'=>lookup::ExpenseNameFromID($model['idexpense']),
		),
		array(
			'name'=>'idacctcredit',
			'value'=>lookup::CashboxNameFromID($model['idacctcredit']),
		),
		array(
			'name'=>'amount',
			'value'=>number_format($model['amount'])
		),
		array(
			'name'=>'userlog',
			'value'=>lookup::UserNameFromUserID($model['userlog'])
		),
		'datetimelog',
	),
)); ?>

<div class="row">
		<?php 
			$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                  'id'=>'AdjustDialog',
                  'options'=>array(
                      'title'=>'Pilih Barang',
                      'autoOpen'=>false,
                      'height'=>200,
                      'width'=>600,
                      'modal'=>true,
                      'buttons'=>array(
                          array('text'=>'Ok', 'click'=>'js:function(){
                             $.get(\'index.php?r=cashouts/default/adjustCashOut\',
                             { idcashout: encodeURI($(\'#Cashouts_id\').val()),
								amount: encodeURI($(\'#Cashouts_amount\').val()),
								periodcount: encodeURI($(\'#Cashouts_periodcount\').val()),
								count: encodeURI($(\'#periodcount\').val())}
                             )
                             $(this).dialog("close");
                           }'),
                          array('text'=>'Close', 'click'=>'js:function(){
                              $(this).dialog("close");
                          }'),
                      ),
                  ),
               ));
               $myd=<<<EOS
         
            <div>Jumlah Periode: <input type="text" name="periodcount" id="periodcount" value="0" size="50"/>
            </div>
EOS;
               echo $myd;
               $this->endWidget('zii.widgets.jui.CJuiDialog');
		?>
	</div>

