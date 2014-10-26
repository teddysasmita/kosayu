<?php

function cmp($a, $b)
{
	return strcmp($a['iditem'], $b['iditem']);
}

function cmp2($a, $b)
{
	return strcmp($a['idatetime'], $b['idatetime']);
}

class DefaultController extends Controller
{
	public $layout='//layouts/column2';
	private $formid = 'AC51';
	private $tracker;
	
	public function actionIndex()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			$this->render('index');
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionQuantity()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			
			$alldata = array();
			$whcodeparam = '';
			$itemnameparam = '';
			$statusparam = '';
				
			if (isset($_GET['go'])) {
				$whcodeparam = $_GET['whcode'];
				$itemnameparam = $_GET['itemname'];
				$statusparam = $_GET['status'];
				$whs = Yii::app()->db->createCommand()
					->select("id, code")->from('warehouses')->where('code like :p_code', 
						array(':p_code'=>'%'.$whcodeparam.'%'))
					->queryAll();	
				foreach($whs as $wh) {
					$command = Yii::app()->db->createCommand()
						->select("count(*) as total, a.iddetail, a.iditem, b.name, '${wh['code']}' as code, a.avail, a.status")
						->from("wh${wh['id']} a")
						->join('items b', 'b.id = a.iditem')
						->where("b.name like :p_name", array(':p_name'=>"%$itemnameparam%"));	
						if ($statusparam !== 'Semua')
							$command->andWhere("a.status = :p_status", array(':p_status'=>$statusparam));
					$data = $command->group(array('iditem', 'avail'))
						->order('a.iditem, a.avail')
						->queryAll();
					$alldata = array_merge($alldata, $data);
				}
				usort($alldata, 'cmp');
			}
			$this->render('quantity', array('alldata'=>$alldata, 'status'=>$statusparam, 
					'whcode'=>$whcodeparam, 'itemname'=>$itemnameparam));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionSerial()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
				
			$alldata = array();
			$whcodeparam = '';
			$itemnameparam = '';
			$statusparam = '';
			
			if (isset($_GET['go'])) {
				$whcodeparam = $_GET['whcode'];
				$itemnameparam = $_GET['itemname'];
				$statusparam = $_GET['status'];
				$whs = Yii::app()->db->createCommand()
				->select("id, code")->from('warehouses')->where('code like :p_code',
						array(':p_code'=>'%'.$whcodeparam.'%'))
						->queryAll();
				foreach($whs as $wh) {
					$command = Yii::app()->db->createCommand()
						->select("a.iddetail, a.iditem, b.name, a.serialnum, concat('${wh['code']}') as code, a.avail, a.status")
						->from("wh${wh['id']} a")
						->join('items b', 'b.id = a.iditem')
						->where("b.name like :p_name", array(':p_name'=>"%$itemnameparam%"));
						
					if ($statusparam !== 'Semua')
						$command->andWhere("a.status = :p_status", array(':p_status'=>$statusparam));
					$data = $command->order('b.name, a.avail')->queryAll();
					$alldata = array_merge($alldata, $data);
				}
				usort($alldata, 'cmp');
			}
			$this->render('serial', array('alldata'=>$alldata, 'status'=>$statusparam,
				'whcode'=>$whcodeparam, 'itemname'=>$itemnameparam));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionTrace()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
	
			$alldata = array();
			$serialnumparam = '';
				
			if (isset($_GET['go'])) {
				$serialnumparam = $_GET['serialnum'];
				$whs = Yii::app()->db->createCommand()
					->select("id, code")->from('warehouses')->queryAll();
				foreach($whs as $wh) {
					$data = Yii::app()->db->createCommand()
						->select("a.iddetail, a.iditem, ('${wh['code']}') as code, a.avail, a.status, b.name")
						->from("wh${wh['id']} a")
						->join('items b', 'b.id = a.iditem')
						->where("a.serialnum = :p_serialnum", 
							array(':p_serialnum'=>$serialnumparam))
						->queryAll();
					if (!$data) {
						$data = Yii::app()->db->createCommand()
						->select("a.iddetail, a.iditem, ('${wh['code']}') as code, a.avail, a.status, ('Tidak Terdaftar') as name")
						->from("wh${wh['id']} a")
						->where("a.serialnum = :p_serialnum",
								array(':p_serialnum'=>$serialnumparam))
								->queryAll();
					}
					$alldata = array_merge($alldata, $data);
				}
			}
			$this->render('trace', array('alldata'=>$alldata, 'serialnum'=>$serialnumparam));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionFlow()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
		
			$alldata = array();
			$iditemparam = '';
			$whcodeparam = '';
				
			if (isset($_GET['go'])) {
				$sql=<<<EOS
	select b.iddetail, a.idwarehouse, a.regnum, a.transname, a.transid, a.idatetime, count(*) as total, b.iditem from stockentries a 
	join detailstockentries b on b.id = a.id
	where b.iditem = :p_b_iditem and a.idwarehouse like :p_a_idwh and b.serialnum <> 'Belum Diterima'
	group by a.regnum
	union
	select d.iddetail, c.idwarehouse, c.regnum, c.transname, c.transid, c.idatetime, - (count(*)) as total, d.iditem from stockexits c 
	join detailstockexits d on d.id = c.id
	where d.iditem = :p_d_iditem and c.idwarehouse like :p_c_idwh and d.serialnum <> 'Belum Diterima'
	group by c.regnum
	order by idatetime							
EOS;
				$iditemparam = $_GET['iditem'];
				$whcodeparam = $_GET['whcode'];
				if ($whcodeparam !== "")
					$idwh = lookup::WarehouseIDFromCode($whcodeparam);
				else
					$idwh = '%';
				$command = Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_b_iditem', $iditemparam, PDO::PARAM_STR);
				$command->bindParam(':p_d_iditem', $iditemparam, PDO::PARAM_STR);
				$command->bindParam(':p_a_idwh', $idwh, PDO::PARAM_STR);
				$command->bindParam(':p_c_idwh', $idwh, PDO::PARAM_STR);
				$alldata = $command->queryAll();
				usort($alldata, 'cmp2');
				foreach ($alldata as & $data) {
					$data['serialnums'] = $this->getSerials($data['regnum'], $data['iditem'], $data['total']);
				}
			}
			$this->render('flow', array('alldata'=>$alldata, 'iditem'=>$iditemparam, 'whcode'=>$whcodeparam));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionIndexError()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			
			$dataProvider=new CActiveDataProvider('Errors',
                  array(
                     'criteria'=>array(
                        'order'=>'id desc'
                     )
                  )
               );
			
			$this->render('indexerror', array('dataProvider'=>$dataProvider));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionViewError($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			$this->render('viewerror',array(
				'model'=>$this->loadError($id),
			));
		} else {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };
	}
	
	public function actionErrorExcel($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			
			$xl = new PHPExcel();
			$xl->getProperties()->setCreator("Program GSI Malang")
				->setLastModifiedBy("Program GSI Malang")
				->setTitle("Laporan Penjualan")
				->setSubject("Laporan Penjualan")
				->setDescription("Laporan Penjualan Bulanan")
				->setKeywords("Laporan Penjualan")
				->setCategory("Laporan");	
			$datas=Yii::app()->db->createCommand()
				->select()->from('detailerrors a')
				->where('id = :p_id', array(':p_id'=>$id))
				->queryAll();
			foreach ($datas as $data) {
				$newdata['iditem'] = $data['iditem'];
				$newdata['itemname'] = lookup::ItemNameFromItemID($data['iditem']);
				$newdata['serialnum'] = $data['serialnum'];
				$temp = explode('-', $data['remark']);
				$newdata['wh'] = lookup::WarehouseNameFromWarehouseID(trim($temp[2]));
				$newdata['regnum'] = trim($temp[0]);
				$newdatas[] = $newdata;
			}
			$headersname = array( 'ID Barang', 'Nama Barang', 'Nomor Serial', 'Gudang', 'Nomor Urut');
			$headersfield = array('iditem', 'itemname', 'serialnum', 'wh', 'regnum');
			for( $i=0;$i<count($headersname); $i++ ) {
				$xl->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($i,1, $headersname[$i]);
			}			
			
			for( $i=0; $i<count($newdatas); $i++){
				for( $j=0; $j<count($headersfield); $j++ ) {
					$cellvalue = $newdatas[$i][$headersfield[$j]];
					$xl->setActiveSheetindex(0)
						->setCellValueByColumnAndRow($j,$i+2, $cellvalue);
				}
			}
			
			$xl->getActiveSheet()->setTitle('Laporan Error');
			$xl->setActiveSheetIndex(0);
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment;filename="stock-error-report-'.idmaker::getDateTime().'.xlsx"');
			header('Cache-Control: max-age=0');
			$xlWriter = PHPExcel_IOFactory::createWriter($xl, 'Excel2007');
			$xlWriter->save('php://output');
		} else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         };
	}
	
	public function loadError($id)
	{
		$model=Errors::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	protected function trackActivity($action)
	{
		$this->tracker=new Tracker();
		$this->tracker->init();
		$this->tracker->logActivity($this->formid, $action);
	}
	
	private function getSerials($regnum, $iditem, $qty)
	{
		if ($qty < 0) {
			$master = 'stockexits a';
			$detail = 'detailstockexits b';
		} else {
			$master = 'stockentries a';
			$detail = 'detailstockentries b';
		}
		$data = Yii::app()->db->createCommand()
			->select('b.serialnum')->from($master)->join($detail, 'b.id = a.id')
			->where("a.regnum = :p_regnum and b.iditem = :p_iditem and b.serialnum <> 'Belum Diterima'",
				array(':p_regnum'=>$regnum, ':p_iditem'=>$iditem))
			->queryColumn();	
		if (is_array($data)) {
			//print_r($data);
			//die;
			return implode(', ', $data);
		} else 
			return $data;
	}
}