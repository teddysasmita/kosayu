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
	public $formid='AC29';
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
			
			$model = new Retrievalreplaces ();
			$this->afterInsert ( $model );
			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation ( $model );
			$error = '';
			if (isset ( $_POST ['yt0'] )) {
				// The user pressed the button;
				$model->attributes = $_POST ['Retrievalreplaces'];
				
				$validdata = $this->checkRetrieval($model->retrievalnum, $model->serialnum) &&
					($_POST['validdata'] == 'true');
				if (!$validdata) {
					$error = 'Proses tidak dapat dilakukan krn ada kesalahan data';
				} else {
					$this->beforePost ( $model );
					$respond = $model->save();
					if (! $respond) 
						throw new CHttpException ( 404, 'Data tidak lengkap.'.print_r($_POST) );
					$this->afterPost ( $model );
					Yii::app ()->session->remove ( 'Retrievalreplaces' );
					$this->redirect ( array (
							'index',
						) );
				}
			}
			$this->render ( 'create', array ('model' => $model, 'error' => $error));
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
			$error = '';
			
				// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation ( $model );
			if (isset ( $_POST ['yt0'] )) {
				$model->attributes = $_POST ['Retrievalreplaces'];
				$validdata = $this->checkRetrieval($model->invnum, $model->serialnum) &&
					($_POST['validdata'] == 'true');

				if (!$validdata) {
					$info = 'Proses tidak dapat dilakukan';
				} else {
					$this->beforePost ( $model );
					$this->tracker->modify ( 'retrievalreplaces', $id );
					$respond = $model->save();
					if (! $respond) 
						throw new CHttpException ( 404, 'Data tidak lengkap.'.print_r($_POST) );
					
					$this->afterPost ( $model );
					Yii::app ()->session->remove ( 'Retrievalreplaces' );
					$this->redirect ( array (
						'view',
						'id' => $model->id 
					));
				}
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
            $this->tracker->delete('retrievalreplaces', $id);

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

               Yii::app()->session->remove('Retrievalreplaces');
               $dataProvider=new CActiveDataProvider('Retrievalreplaces',
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
			
			$model = new Retrievalreplaces ( 'search' );
			$model->unsetAttributes (); // clear any default values
			if (isset ( $_GET ['Retrievalreplaces'] ))
				$model->attributes = $_GET ['Retrievalreplaces'];
			
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
			$this->tracker->restore ( 'retrievalreplaces', $idtrack );
			
			$dataProvider = new CActiveDataProvider ( 'Retrievalreplaces' );
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
			$id = Yii::app ()->tracker->createCommand ()->select ( 'id' )->from ( 'retrievalreplaces' )->where ( 'idtrack = :p_idtrack', array (
					':p_idtrack' => $idtrack 
			) )->queryScalar ();
			$this->tracker->restoreDeleted ( 'detailretrievalreplaces', "id", $id );
			$this->tracker->restoreDeleted ( 'retrievalreplaces', "idtrack", $idtrack );
			
			$dataProvider = new CActiveDataProvider ( 'Retrievalreplaces' );
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
	 * @return Retrievalreplaces the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Retrievalreplaces::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Retrievalreplaces $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='retrievalreplaces-form')
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
		//$model->idwarehouse=lookup::WarehouseNameFromIpAddr($_SERVER['REMOTE_ADDR']);
	}
	
	protected function afterPost(& $model) {
		$idmaker = new idmaker ();
		if ($this->state == 'create') {
			$idmaker->saveRegNum ( $this->formid, substr($model->regnum, 2) );
			
		} else if ($this->state == 'update') {
			$tempid = $model->id;
			$tempid = substr($tempid, 0, 20).'E';
			Yii::import('application.modules.stockentries.models.*');
			$stockentries = Stockentries::findByPk($tempid);
			if (! is_null($stockentries))
				$stockentries->delete();
			$detailstockentries = Detailstockentries::model()->findAllByAttributes('id', $tempid);
			if (count($detailstockentries) > 0)
			foreach($detailstockentries as $dse) {
				$dse->delete();
			};
		}
		Yii::import('application.modules.stockentries.models.*');
		$stockentries = new Stockentries();
		$tempid = $model->id;
		$tempid = substr($tempid, 0, 20).'E';
		$stockentries->id = $tempid;
		$stockentries->userlog = $model->userlog;
		$stockentries->datetimelog = idmaker::getDateTime();
		$stockentries->transid = $model->regnum;
		$stockentries->transname = 'AC29';
		$stockentries->transinfo = 'Penukaran Pengambilan Barang - ' + $model->regnum + ' - ' +
		$model->idatetime;
		$stockentries->idwarehouse = $model->idwarehouse;
		$stockentries->donum = $model->regnum;
		$stockentries->idatetime = $model->idatetime;
		$stockentries->regnum = idmaker::getRegNum('AC3');
		idmaker::saveRegNum('AC3', $stockentries->regnum);
		if ($stockentries->validate())
			$stockentries->save();
		else
			throw new CHttpException(101,'Error in Stock Entry.');
		Action::setItemStatusinWarehouse($model->idwarehouse, $model->serialnum, '1');
		
		$detailstockentries = new Detailstockentries();
		$detailstockentries->id = $stockentries->id;
		$detailstockentries->iddetail = idmaker::getCurrentID2();
		$detailstockentries->iditem = $model->iditem;
		$detailstockentries->serialnum = $model->serialnum;
		$detailstockentries->userlog = $model->userlog;
		$detailstockentries->datetimelog = idmaker::getDateTime();
		$detailstockentries->status = '2';
		if ($detailstockentries->validate())
			$detailstockentries->save();
		else
			throw new CHttpException(101,'Error in Detail Stock Entry.');
		$exist = Action::checkItemToWarehouse($model->idwarehouse, $model->iditem,
				$model->serialnum, '%') > 0;
		if (!$exist)
			Action::addItemToWarehouse($model->idwarehouse, $model->id,
					$model->iditem, $model->serialnum);
		else
			Action::setItemAvailinWarehouse($model->idwarehouse, $model->serialnum, '1');
	}
	
	protected function beforePost(& $model) {
		$idmaker = new idmaker ();
		
		$model->userlog = Yii::app ()->user->id;
		$model->datetimelog = $idmaker->getDateTime ();
		if ($this->state == 'create')
			$model->regnum = 'RE'.$idmaker->getRegNum ( $this->formid );
	}
	
	protected function beforeDelete(& $model) {
		$tempid = $model->id;
		$tempid = substr($tempid, 0, 20).'E';
		Yii::import('application.modules.stockentries.models.*');
		
		$stockentries = Stockentries::model()->findByPk($tempid);
		if (! is_null($stockentries))
			$stockentries->delete();
		$detailstockentries = Detailstockentries::model()->findAllByAttributes(array('id'=>$tempid));
		if (count($detailstockentries) > 0)
		foreach($detailstockentries as $dse) {
			$dse->delete();
		};
		Action::setItemAvailinWarehouse($model->idwarehouse, $model->serialnum, 0);
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
      				->from("retrievalreplaces a")
      				->join("detailretrievalreplaces c", "c.id = a.id")
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
	
	private function checkRetrieval($retrievalnum, $serialnum)
	{
		$data=Yii::app()->db->createCommand()
			->select('b.serialnum, b.iditem')
			->from('stockexits a')
			->join('detailstockexits b', 'b.id = a.id')
			->where('a.transid = :p_transid and b.serialnum = :p_serialnum',
				array(':p_transid'=>$retrievalnum, ':p_serialnum'=>$serialnum))
			->queryAll();
		if (count($data)>0) {
			return true;
		} else
			return false;
	}
}