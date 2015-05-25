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
	private $formid = 'AC30';
	private $tracker;
	
	public function actionIndex()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			
			Yii::app()->session->remove('stockquantityreport');
			Yii::app()->session->remove('stockquantitydate');
			Yii::app()->session->remove('stockquantityprefix');
			Yii::app()->session->remove('stockflowreport');
			Yii::app()->session->remove('stockflowend');
			Yii::app()->session->remove('stockflowstart');
			Yii::app()->session->remove('stockflowprefix');
				
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
			
			if (!isset(Yii::app()->session['stockquantityreport'])) {
				$alldata = array();
				$dateparam = idmaker::getDateTime();
				$prefixparam = '';;
			} else {
				$dateparam = Yii::app()->session['stockquantitydate'];
				$prefixparam = Yii::app()->session['stockquantityprefix'];
			} 
			if (isset($_POST['go'])) {
				$dateparam = substr($_POST['cdate'], 0, 10).' 23:59:59';
				$prefixparam = $_POST['cprefix'];
				$alldata = Yii::app()->db->createCommand()
					->select("b.batchcode, c.name, sum(b.qty) as totalqty")
					->from('detailstocks b')
					->join('stocks a', 'a.id = b.id')
					->join('items c', 'c.id = b.iditem')
					->where('a.idatetime <= :p_cdate', 
						array(':p_cdate'=>$dateparam))
					->andWhere('b.batchcode like :p_batchcode', array(':p_batchcode'=>$prefixparam.'%'))
					->group('b.batchcode')
					->order('b.batchcode')
					->queryAll();	
				foreach($alldata as & $ad) {
					$ad['buyprice'] = lookup::getbuyprice($ad['batchcode']);
					$ad['sellprice'] = lookup::getsellprice($ad['batchcode']);
				}
				unset($ad);
				Yii::app()->session['stockquantityreport'] = $alldata;
				Yii::app()->session['stockquantitydate'] = $dateparam;
				Yii::app()->session['stockquantityprefix'] = $prefixparam;
			}
				
			$this->render('quantity', array('cdate'=>substr($dateparam, 0, 10), 
				'cprefix'=>$prefixparam)
			);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionFlow()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
		
			if (!isset(Yii::app()->session['stockflowreport'])) {
				$postdata = array();
				$startparam = idmaker::getDateTime();
				$endparam = idmaker::getDateTime();
				$prefixparam = '';;
			} else {
				$startparam = Yii::app()->session['stockflowstart'];
				$endparam = Yii::app()->session['stockflowend'];
				$prefixparam = Yii::app()->session['stockquantityprefix'];
			} 
			if (isset($_POST['go'])) {
				$startparam = substr($_POST['cstart'], 0, 10).' 00:00:00';
				$endparam = substr($_POST['cend'], 0, 10).' 23:59:59';
				$prefixparam = $_POST['cprefix'];
				
				$predata = Yii::app()->db->createCommand()
					->select("b.batchcode, c.name, sum(b.qty) as totalqty")
					->from('detailstocks b')
					->join('stocks a', 'a.id = b.id')
					->join('items c', 'c.id = b.iditem')
					->where('a.idatetime <= :p_cstart', 
						array(':p_cstart'=>$startparam))
					->andWhere('b.batchcode like :p_batchcode', array(':p_batchcode'=>$prefixparam.'%'))
					->group('b.batchcode')
					->order('b.batchcode')
					->queryAll();	
				
				$receivedata = Yii::app()->db->createCommand()
					->select("b.batchcode, c.name, sum(b.qty) as totalqty")
					->from('detailstocks b')
					->join('stocks a', 'a.id = b.id')
					->join('items c', 'c.id = b.iditem')
					->where('a.idatetime >= :p_cstart and a.idatetime <= :p_cend',
						array(':p_cstart'=>$startparam, ':p_cend'=>$endparam))
					->andWhere('b.batchcode like :p_batchcode', array(':p_batchcode'=>$prefixparam.'%'))
					->andWhere('a.transtype = :p_transtype1 or a.transtype = :p_transtype2', 
						array(':p_transtype1'=>'Beli Putus', ':p_transtype2'=>'Beli Konsinyasi'))
					->group('b.batchcode')
					->order('b.batchcode')
					->queryAll();
				
				$solddata = Yii::app()->db->createCommand()
					->select("b.batchcode, c.name, sum(b.qty) as totalqty")
					->from('detailstocks b')
					->join('stocks a', 'a.id = b.id')
					->join('items c', 'c.id = b.iditem')
					->where('a.idatetime >= :p_cstart and a.idatetime <= :p_cend',
						array(':p_cstart'=>$startparam, ':p_cend'=>$endparam))
					->andWhere('b.batchcode like :p_batchcode', array(':p_batchcode'=>$prefixparam.'%'))
					->andWhere('a.transtype = :p_transtype',
						array(':p_transtype'=>'Penjualan'))
					->group('b.batchcode')
					->order('b.batchcode')
					->queryAll();
				
				$returdata = Yii::app()->db->createCommand()
					->select("b.batchcode, c.name, sum(b.qty) as totalqty")
					->from('detailstocks b')
					->join('stocks a', 'a.id = b.id')
					->join('items c', 'c.id = b.iditem')
					->where('a.idatetime >= :p_cstart and a.idatetime <= :p_cend',
						array(':p_cstart'=>$startparam, ':p_cend'=>$endparam))
					->andWhere('b.batchcode like :p_batchcode', array(':p_batchcode'=>$prefixparam.'%'))
					->andWhere('a.transtype = :p_transtype',
						array(':p_transtype'=>'Retur Beli'))
					->group('b.batchcode')
					->order('b.batchcode')
					->queryAll();
				
				$salereturdata = Yii::app()->db->createCommand()
					->select("b.batchcode, c.name, sum(b.qty) as totalqty")
					->from('detailstocks b')
					->join('stocks a', 'a.id = b.id')
					->join('items c', 'c.id = b.iditem')
					->where('a.idatetime >= :p_cstart and a.idatetime <= :p_cend',
							array(':p_cstart'=>$startparam, ':p_cend'=>$endparam))
					->andWhere('b.batchcode like :p_batchcode', array(':p_batchcode'=>$prefixparam.'%'))
					->andWhere('a.transtype = :p_transtype',
								array(':p_transtype'=>'Retur Jual'))
					->group('b.batchcode')
					->order('b.batchcode')
					->queryAll();
				
				$stockadjustdata = Yii::app()->db->createCommand()
					->select("b.batchcode, c.name, sum(b.qty) as totalqty")
					->from('detailstocks b')
					->join('stocks a', 'a.id = b.id')
					->join('items c', 'c.id = b.iditem')
					->where('a.idatetime >= :p_cstart and a.idatetime <= :p_cend',
							array(':p_cstart'=>$startparam, ':p_cend'=>$endparam))
					->andWhere('b.batchcode like :p_batchcode', array(':p_batchcode'=>$prefixparam.'%'))
					->andWhere('a.transtype = :p_transtype',
							array(':p_transtype'=>'Penyesuaian'))
					->group('b.batchcode')
					->order('b.batchcode')
					->queryAll();
				
				$postdata = Yii::app()->db->createCommand()
					->select("b.batchcode, c.name, sum(b.qty) as endqty")
					->from('detailstocks b')
					->join('stocks a', 'a.id = b.id')
					->join('items c', 'c.id = b.iditem')
					->where('a.idatetime <= :p_cend',
						array(':p_cend'=>$endparam))
					->andWhere('b.batchcode like :p_batchcode', array(':p_batchcode'=>$prefixparam.'%'))
					->group('b.batchcode')
					->order('b.batchcode')
					->queryAll();
				
				foreach($predata as & $pr) {
					$found = FALSE;
					foreach($postdata as & $ps) {
						if ($pr['batchcode'] == $ps['batchcode']) {
							$found = TRUE;
							$ps['startqty'] = $pr['totalqty'];
							break;
						}
					}
					if (!$found) {
						$temp['batchcode'] = $pr['batchcode'];
						$temp['name'] = $pr['name'];
						$temp['startqty'] = $pr['totalqty'];
						$temp['endqty'] = 0;
						$postdata[] = $temp;
					}
				}
				
				unset($pr);
				unset($ps);
				foreach($postdata as & $ps) {
					$found = FALSE;
					foreach($receivedata as $rc) {
						if ($ps['batchcode'] == $rc['batchcode']) {
							$found = TRUE;
							$ps['receiveqty'] = $rc['totalqty'];
							break;
						}
					}
					if (!$found) {
						$ps['receiveqty'] = 0;
					}		
					$found = FALSE;
					foreach($solddata as $sd) {
						if ($ps['batchcode'] == $sd['batchcode']) {
							$found = TRUE;
							$ps['soldqty'] = $sd['totalqty'];
							break;
						}
					}
					if (!$found) {
						$ps['soldqty'] = 0;
					}
					$found = FALSE;
					foreach($returdata as $rt) {
						if ($ps['batchcode'] == $rt['batchcode']) {
							$found = TRUE;
							$ps['returqty'] = $rt['totalqty'];
							break;
						}
					}
					if (!$found) {
						$ps['returqty'] = 0;
					}
					$found = FALSE;
					foreach($salereturdata as $srt) {
						if ($ps['batchcode'] == $srt['batchcode']) {
							$found = TRUE;
							$ps['salereturqty'] = $srt['totalqty'];
							break;
						}
					}
					if (!$found) {
						$ps['salereturqty'] = 0;
					}
					$found = FALSE;
					foreach($stockadjustdata as $srt) {
						if ($ps['batchcode'] == $srt['batchcode']) {
							$found = TRUE;
							$ps['stockadjustqty'] = $srt['totalqty'];
							break;
						}
					}
					if (!$found) {
						$ps['stockadjustqty'] = 0;
					}	
				}
				
				foreach($postdata as & $ps) {
					if (!isset($ps['startqty'])) {
						$ps['startqty'] = 0;
					}
				}
				
				Yii::app()->session['stockflowreport'] = $postdata;
				Yii::app()->session['stockflowend'] = $endparam;
				Yii::app()->session['stockflowstart'] = $startparam;
				Yii::app()->session['stockflowprefix'] = $prefixparam;
			}
				
			$this->render('flow', array(
				'cstart'=>substr($startparam, 0, 10), 
				'cend'=>substr($endparam, 0, 10),
				'cprefix'=>$prefixparam
			));
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
	
	public function actionStockFlow()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
			$reportdata = Yii::app()->session['stockflowreport'];
			$suppliercode = Yii::app()->session['stockflowprefix'];
			$startdate = Yii::app()->session['stockflowstart'];
			$enddate = Yii::app()->session['stockflowend'];	
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			Yii::import('application.modules.stockadmin.components.*');
			require_once('print_stockflow.php');
			ob_clean();
			execute($reportdata, $suppliercode, $startdate, $enddate);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
	}
}