<?php

function cmp($a, $b)
{
	return strcmp($a['iditem'], $b['iditem']);
}

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC30';
	public $tracker;
	public $state;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			$this->render('view',array(
				'model'=>$this->loadModel($id),
			));
		} else {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };	
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                    Yii::app()->user->id))  {   
			$this->state = 'create';
			$this->trackActivity ( 'c' );
			
			$model = new Displayexits ();
			$this->afterInsert ( $model );
			
			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation ( $model );
			$info = '';
			if (isset ( $_POST ['Displayexits'] )) {
				// The user pressed the button;
				$model->attributes = $_POST ['Displayexits'];
				
				$dataexit = $this->checkSerial($model->serialnum, $model->idwarehouse);
				if ($dataexit === FALSE) {
					$info = 'Data Barang tidak ditemukan';
				} else {
					$info = 'Permintaan Barang Keluar no. ' .$dataexit['regnum']. ' - '. $dataexit['idatetime']. ' - '.
						lookup::SalesNameFromID($dataexit['idsales']). '<br>'.
						lookup::ItemNameFromItemID($dataexit['iditem']). '<br>'.
						'Keluar Gudang no. '. $dataexit['stocknum']. ' - '. $dataexit['stocktime'].' - '.
						lookup::WarehouseNameFromWarehouseID($dataexit['idwarehouse']);
					$model->iditem = $dataexit['iditem'];
					$model->avail = $dataexit['avail'];
					$model->transid = $dataexit['regnum'];
					$this->beforePost ( $model );
					$respond = $model->save();
					if (! $respond)
						throw new CHttpException ( 404, 'Data tidak lengkap.' );
					$this->afterPost ( $model );
					Yii::app ()->session->remove ( 'Displayexits' );
				}
			}
			$this->render ( 'create', array ('model' => $model, 'info' => $info));
		} else {
			throw new CHttpException ( 404, 'You have no authorization for this operation.' );
		}           
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		if (Yii::app ()->authManager->checkAccess ( $this->formid . '-Update', Yii::app ()->user->id )) {
			
			$this->state = 'update';
			$this->trackActivity ( 'u' );
			
			$model = $this->loadModel ( $id );
			$this->afterEdit ( $model );
			
			Yii::app ()->session ['master'] = 'update';
			
			if (! isset ( Yii::app ()->session ['Displayexits'] ))
				Yii::app ()->session ['Displayexits'] = $model->attributes;
			else
				$model->attributes = Yii::app ()->session ['Displayexits'];
				
				// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation ( $model );
			if (isset ( $_POST ['yt0'] )) {
				$model->attributes = $_POST ['Displayexits'];
				$this->beforePost ( $model );
				$this->tracker->modify ( 'displayexits', $id );
				$respond = $model->save ();
				if (!$respond) 
					throw new CHttpException ( 404, 'Data tidak lengkap' );
				$this->afterPost ( $model );
				Yii::app ()->session->remove ( 'Displayexits' );
				$this->redirect ( array (
						'view',
						'id' => $model->id 
				) );
			}
			$this->render ( 'update', array (
					'model' => $model 
			) );
		} else {
			throw new CHttpException ( 404, 'You have no authorization for this operation.' );
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
         if(Yii::app()->authManager->checkAccess($this->formid.'-Delete', 
                 Yii::app()->user->id))  {

            $model=$this->loadModel($id);
            $this->trackActivity('d');
            $this->beforeDelete($model);
            $this->tracker->delete('displayexits', $id);

            $model->delete();
            $this->afterDelete($model);

         // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
         if(!isset($_GET['ajax']))
               $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
     }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
            if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
                Yii::app()->user->id)) {
               $this->trackActivity('l');

               Yii::app()->session->remove('Displayexits');
               $dataProvider=new CActiveDataProvider('Displayexits',
                  array(
                     'criteria'=>array(
                        'order'=>'id desc'
                     )
                  )
               );
               $this->render('index',array(
                     'dataProvider'=>$dataProvider,
               ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		if (Yii::app ()->authManager->checkAccess ( $this->formid . '-List', Yii::app ()->user->id )) {
			$this->trackActivity ( 's' );
			
			$model = new Displayexits ( 'search' );
			$model->unsetAttributes (); // clear any default values
			if (isset ( $_GET ['Displayexits'] ))
				$model->attributes = $_GET ['Displayexits'];
			
			$this->render ( 'admin', array (
					'model' => $model 
			) );
		} else {
			throw new CHttpException ( 404, 'You have no authorization for this operation.' );
		}
	}
	public function actionHistory($id) {
		if (Yii::app ()->authManager->checkAccess ( $this->formid . '-Update', Yii::app ()->user->id )) {
			$model = $this->loadModel ( $id );
			$this->render ( 'history', array (
					'model' => $model 
			)
			 );
		} else {
			throw new CHttpException ( 404, 'You have no authorization for this operation.' );
		}
	}
	public function actionDeleted() {
		if (Yii::app ()->authManager->checkAccess ( $this->formid . '-Update', Yii::app ()->user->id )) {
			$this->render ( 'deleted', array ()

			 );
		} else {
			throw new CHttpException ( 404, 'You have no authorization for this operation.' );
		}
	}
	public function actionRestore($idtrack) {
		if (Yii::app ()->authManager->checkAccess ( $this->formid . '-Update', Yii::app ()->user->id )) {
			$this->trackActivity ( 'r' );
			$this->tracker->restore ( 'displayexits', $idtrack );
			
			$dataProvider = new CActiveDataProvider ( 'Displayexits' );
			$this->render ( 'index', array (
					'dataProvider' => $dataProvider 
			) );
		} else {
			throw new CHttpException ( 404, 'You have no authorization for this operation.' );
		}
	}
	public function actionRestoreDeleted($idtrack) {
		if (Yii::app ()->authManager->checkAccess ( $this->formid . '-Update', Yii::app ()->user->id )) {
			$this->trackActivity ( 'n' );
			$id = Yii::app ()->tracker->createCommand ()->select ( 'id' )->from ( 'displayexits' )->where ( 'idtrack = :p_idtrack', array (
					':p_idtrack' => $idtrack 
			) )->queryScalar ();
			$this->tracker->restoreDeleted ( 'detaildisplayexits', "id", $id );
			$this->tracker->restoreDeleted ( 'displayexits', "idtrack", $idtrack );
			
			$dataProvider = new CActiveDataProvider ( 'Displayexits' );
			$this->render ( 'index', array (
					'dataProvider' => $dataProvider 
			) );
		} else {
			throw new CHttpException ( 404, 'You have no authorization for this operation.' );
		}
	}
        
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Displayexits the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Displayexits::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Displayexits $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='displayexits-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	protected function afterInsert(& $model) {
		$idmaker = new idmaker ();
		$model->id = $idmaker->getCurrentID2 ();
		$model->idatetime = $idmaker->getDateTime ();
		$model->regnum = $idmaker->getRegNum ( $this->formid );
		$model->userlog = Yii::app ()->user->id;
		$model->datetimelog = $idmaker->getDateTime ();
		$model->idwarehouse=lookup::WarehouseNameFromIpAddr($_SERVER['REMOTE_ADDR']);
	}
	
	protected function afterPost(& $model) {
		$idmaker = new idmaker ();
		Yii::import('application.modules.stockexits.models.*');
		if ($this->state == 'create') {
			$idmaker->saveRegNum ( $this->formid, substr($model->regnum, 2) );
			
		} else if ($this->state == 'update') {
			$tempid = $model->id;
			$tempid = substr($tempid, 0, 20).'E';
			$stockexits = Stockexits::findByPk($tempid);
			if (! is_null($stockexits))
				$stockexits->delete();
			$detailstockexits = Detailstockexits::model()->findAllByAttributes('id', $tempid);
			if (count($detailstockexits) > 0)
			foreach($detailstockexits as $dse) {
				$dse->delete();
			};
		}
		$stockexits = new Stockexits();
		$tempid = $model->id;
		$tempid = substr($tempid, 0, 20).'E';
		$stockexits->id = $tempid;
		$stockexits->userlog = $model->userlog;
		$stockexits->datetimelog = idmaker::getDateTime();
		$stockexits->transid = $model->regnum;
		$stockexits->transname = 'AC31';
		$stockexits->transinfo = 'Barang Keluar Display - ' + $model->regnum + ' - ' +
		$model->idatetime;
		$stockexits->idwarehouse = $model->idwarehouse;
		$stockexits->donum = $model->regnum;
		$stockexits->idatetime = $model->idatetime;
		$stockexits->regnum = idmaker::getRegNum('AC3');
		idmaker::saveRegNum('AC3', $stockexits->regnum);
		if ($stockexits->validate())
			$stockexits->save();
		else
			throw new CHttpException(101,'Error in Stock Exit.');
		Action::setItemStatusinWarehouse($model->idwarehouse, $model->serialnum, '1');
		
		$detailstockexits = new Detailstockexits();
		$detailstockexits->id = $stockexits->id;
		$detailstockexits->iddetail = idmaker::getCurrentID2();
		$detailstockexits->iditem = $model->iditem;
		$detailstockexits->serialnum = $model->serialnum;
		$detailstockexits->avail = '1';
		$detailstockexits->userlog = $model->userlog;
		$detailstockexits->datetimelog = idmaker::getDateTime();
		if ($detailstockexits->validate())
			$detailstockexits->save();
		else
			throw new CHttpException(101,'Error in Detail Stock Exit.');
		$exist = Action::checkItemToWarehouse($model->idwarehouse, $model->iditem,
				$model->serialnum, '%') > 0;
		if (!$exist)
			Action::addItemToWarehouse($model->idwarehouse, $model->id,
					$model->iditem, $model->serialnum);
		else
			Action::setItemStatusinWarehouse($model->idwarehouse, $model->serialnum, $model->avail);
	}
	
	protected function beforePost(& $model) {
		$idmaker = new idmaker ();
		
		$model->userlog = Yii::app ()->user->id;
		$model->datetimelog = $idmaker->getDateTime ();
		if ($this->state == 'create')
			$model->regnum = 'DX'.$idmaker->getRegNum ( $this->formid );
	}
	
	protected function beforeDelete(& $model) {
		$tempid = $model->id;
		$tempid = substr($tempid, 0, 20).'D';
		Yii::import('application.modules.stockexits.models.*');
		
		$stockexits = Stockexits::model()->findByPk($tempid);
		if (! is_null($stockexits))
			$stockexits->delete();
		$detailstockexits = Detailstockexits::model()->findAllByAttributes(array('id'=>$tempid));
		if (count($detailstockexits) > 0)
		foreach($detailstockexits as $dse) {
			$dse->delete();
		};
		Action::setItemStatusinWarehouse($model->idwarehouse, $model->serialnum, 0);
	}
	
	protected function afterDelete(& $model) {
	
	}
	
	protected function afterEdit(& $model) {
	
	}


     protected function trackActivity($action)
     {
         $this->tracker=new Tracker();
         $this->tracker->init();
         $this->tracker->logActivity($this->formid, $action);
     }
     
      private function checkWarehouse($idwarehouse)
      {
         $respond=$idwarehouse<>'NA';
         if (!$respond)
           throw new CHttpException(404,'Gudang belum terdaftar.'); 
         else
            return $respond; 
      }
      
      public function actionSummary($id)
      {
      	$this->trackActivity('v');
      	$this->render('summary',array(
      			'model'=>$this->loadModel($id),
      	));
      
      }
      
      public function actionPrintsummary($id)
      {
      	$this->trackActivity('v');
      	Yii::import("application.vendors.tcpdf.*");
      	require_once('tcpdf.php');
      	$this->render('printsummary',array(
      			'model'=>$this->loadModel($id),
      	));
      }
      
      public function actionSerial()
      {
      	if(Yii::app()->authManager->checkAccess($this->formid.'-List',
      			Yii::app()->user->id))  {
      		$this->trackActivity('v');
      
      		$alldata = array();
      		$whcodeparam = '';
      		$itemnameparam = '';
      			
      		if (isset($_GET['go'])) {
      			$whcodeparam = $_GET['whcode'];
      			$itemnameparam = $_GET['itemname'];
      			$whs = Yii::app()->db->createCommand()
      			->select("id, code")->from('warehouses')->where('code like :p_code',
      					array(':p_code'=>'%'.$whcodeparam.'%'))
      					->queryAll();
      			foreach($whs as $wh) {
      				$data = Yii::app()->db->createCommand()
      				->select("c.iddetail, a.transid, c.iditem, b.name, c.serialnum, concat('${wh['code']}') as code")
      				->from("displayexits a")
      				->join("detaildisplayexits c", "c.id = a.id")
      				->join('items b', 'b.id = c.iditem')
      				->where("b.name like :p_name and a.idwarehouse = '${wh['id']}' and c.serialnum <> 'Belum Diterima'", array(':p_name'=>"%$itemnameparam%"))
      				->order('b.name')
      				->queryAll();
      				$alldata = array_merge($alldata, $data);
		      	}
		      	usort($alldata, 'cmp');
		      }
		      $this->render('serial', array('alldata'=>$alldata, 'whcode'=>$whcodeparam, 'itemname'=>$itemnameparam));
	      } else {
	      	throw new CHttpException(404,'You have no authorization for this operation.');
	      };
      }
      
      public function actionSerialScan()
      {
      	if(Yii::app()->authManager->checkAccess($this->formid.'-List',
      			Yii::app()->user->id))  {
      		$this->trackActivity('v');
      
      		$alldata = array();
      		$whcodeparam = '';
      		$itemnameparam = '';
      		 
      		if (isset($_GET['go'])) {
      			$whcodeparam = $_GET['whcode'];
      			$itemnameparam = $_GET['itemname'];
      			$whs = Yii::app()->db->createCommand()
      			->select("id, code")->from('warehouses')->where('code like :p_code',
      					array(':p_code'=>'%'.$whcodeparam.'%'))
      					->queryAll();
      			foreach($whs as $wh) {
      				$data = Yii::app()->db->createCommand()
      				->select("c.iddetail, a.regnum, a.idatetime, a.iditem, b.name, c.serialnum, concat('${wh['code']}') as code")
      				->from("acquisitions a")
      				->join("detailacquisitions c", "c.id = a.id")
      				->join('items b', 'b.id = a.iditem')
      				->where("b.name like :p_name and a.idwarehouse = '${wh['id']}'", 
      					array(':p_name'=>"%$itemnameparam%"))
      				->order('b.name')
      				->queryAll();
      				$alldata = array_merge($alldata, $data);
		      	}
		      	usort($alldata, 'cmp');
			}
			$this->render('serialscan', array('alldata'=>$alldata, 'whcode'=>$whcodeparam, 'itemname'=>$itemnameparam));
		} else {
	      	throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	private function checkSerial($serialnum, $idwh)
	{
		$indisplay = Yii::app()->db->createCommand()
			->select('count(*) as total')->from('wh'.$idwh)
			->where('serialnum = :p_serialnum and avail = :p_avail', 
				array(':p_serialnum'=>$serialnum, ':p_avail'=>'1'))
			->queryScalar();
		if ($indisplay == 0) {
			$iditem = Yii::app()->db->createCommand()
				->select('iditem')->from('wh'.$idwh)
				->where('serialnum = :p_serialnum and avail = :p_avail', 
				array(':p_serialnum'=>$serialnum, ':p_avail'=>'1'))
			->queryScalar();
			
			$dataexit = Yii::app()->db->createCommand()
				->select("a.regnum, a.idatetime, concat('AC19') as transname, c.iditem, c.avail")
				->from('orderretrievals a')->join('stockexits b', 'b.transid = a.regnum')
				->join('detailstockexits c', 'c.id = b.id')
				->where('c.serialnum = :p_serialnum', array(':p_serialnum'=>$serialnum))
				->queryRow();
			if (!$dataexit) {
				$dataexit = Yii::app()->db->createCommand()
				->select("a.regnum, a.idatetime, concat('AC13') as transname, b.regnum as stocknum, b.idatetime as stocktime, b.idwarehouse, c.iditem, c.avail")
				->from('deliveryorders a')->join('detaildeliveryorders b', 'b.id = a.id')
				->where('c.serialnum = :p_serialnum', array(':p_serialnum'=>$serialnum))
				->queryRow();
			}
			
			
			return $dataexit;
		} else 
			return FALSE;
	}
}