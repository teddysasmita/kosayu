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
	public $formid='AC32';
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
			
			$model = new Itemmastermods ();
			$this->afterInsert ( $model );
			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation ( $model );
			$error = '';
			
			if (isset ( $_POST ['yt1'] )) {
				// The user pressed the button;
				$model->attributes = $_POST ['Itemmastermods'];
				
				$this->beforePost ( $model );
				$respond = $model->save();
				if (! $respond) 
					throw new CHttpException ( 404, 'Data tidak lengkap.'.print_r($_POST) );
				
				$details = Yii::app()->session['Detailitemmastermods'];
				$respond = $this->saveNewDetails($details);
				if (! $respond)
					throw new CHttpException ( 404, 'Data Detil tidak bisa disimpan.'.print_r($_POST) );
				
				$this->afterPost ( $model );
				Yii::app ()->session->remove ( 'Itemmastermods' );
				Yii::app ()->session->remove ( 'Detailitemmastermods' );
				
				$this->redirect ( array (
					'index',
				) );
			} else if (isset($_POST['command'])) {
				if( $_POST['command'] == 'scanItems') {
					$model->attributes = $_POST ['Itemmastermods'];
					Yii::app()->session['Detailitemmastermods'] = $this->scanItems($model);
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
				$model->attributes = $_POST ['Itemmastermods'];
				
				$this->beforePost ( $model );
				$this->tracker->modify ( 'itemmastermods', $id );
				$respond = $model->save();
				if (! $respond) 
					throw new CHttpException ( 404, 'Data tidak lengkap.'.print_r($_POST) );
				
				$details = Yii::app()->session['Detailitemmastermods'];
				$respond = $this->saveNewDetails($details);
				if (! $respond)
					throw new CHttpException ( 404, 'Data Detil tidak bisa disimpan.'.print_r($_POST) );
				
				$this->afterPost ( $model );
				Yii::app ()->session->remove ( 'Itemmastermods' );
				Yii::app ()->session->remove ( 'Detailitemmastermods' );
				$this->redirect ( array (
					'view',
					'id' => $model->id 
				));
			} else if (isset($_POST['command'])) {
				if( $_POST['command'] == 'scanItems') {
					$model->attributes = $_POST ['Itemmastermods'];
					Yii::app()->session['Detailitemmastermods'] = $this->scanItems($model);
				}
			}
			$this->render ( 'update', array (
					'model' => $model, 'error' => $error
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

            $detailmodels=Detailitemmastermods::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailitemmastermods', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }
            
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

               Yii::app()->session->remove('Itemmastermods');
               $dataProvider=new CActiveDataProvider('Itemmastermods',
                  array(
                     'criteria'=>array(
                        'order'=>'id desc'
                     )
                  )
               );
               Yii::app ()->session->remove ( 'Itemmastermods' );
               Yii::app ()->session->remove ( 'Detailitemmastermods' );
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
			
			$model = new Itemmastermods ( 'search' );
			$model->unsetAttributes (); // clear any default values
			if (isset ( $_GET ['Itemmastermods'] ))
				$model->attributes = $_GET ['Itemmastermods'];
			
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
			
			$dataProvider = new CActiveDataProvider ( 'Itemmastermods' );
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
			
			$dataProvider = new CActiveDataProvider ( 'Itemmastermods' );
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
	 * @return Itemmastermods the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Itemmastermods::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Itemmastermods $model the model to be validated
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
		} 
		$this->append_stockentries($model);
		$this->append_stockexits($model);
	}
	
	protected function beforePost(& $model) 
	{
		$idmaker = new idmaker ();
		
		$model->userlog = Yii::app ()->user->id;
		$model->datetimelog = $idmaker->getDateTime ();
		if ($this->state == 'create')
			$model->regnum = 'IM'.$idmaker->getRegNum ( $this->formid );
		else if ($this->state == 'update') {
			$this->remove_stockexits($model);
			$this->remove_stockentries($model);
		}
	}
	
	protected function beforeDelete(& $model) 
	{
		$this->remove_stockentries($model);
		$this->remove_stockexits($model);
	}
	
	protected function afterDelete(& $model) 
	{
	
	}
	
	protected function afterEdit(& $model) 
	{
	
	}

	
    protected function trackActivity($action)
    {
		$this->tracker=new Tracker();
        $this->tracker->init();
        $this->tracker->logActivity($this->formid, $action);
    }
     
    protected function saveNewDetails(array $details)
    {
    	foreach ($details as $row) {
    		$detailmodel=new Detailitemmastermods;
    		$detailmodel->attributes=$row;
    		$respond=$detailmodel->insert();
    		if (!$respond) {
    			break;
    		}
    	}
    	return $respond;
    }
    
    protected function loadDetails($id)
    {
    	$sql="select * from detailitemmastermods where id='$id'";
    	$details=Yii::app()->db->createCommand($sql)->queryAll();
    
    	return $details;
    }
    
    
    protected function deleteDetails(array $details)
    {
    	$respond=true;
    	foreach ($details as $row) {
    		$detailmodel=Detailitemmastermods::model()->findByPk($row['iddetail']);
    		if($detailmodel) {
    			$this->tracker->init();
    			$this->trackActivity('d', 'AC32a');
    			$this->tracker->delete('detailitemmastermods', $detailmodel->iddetail);
    			$respond=$detailmodel->delete();
    			if (!$respond) {
    				break;
    			}
    		}
    	}
    	return $respond;
    }
    
    private function scanItems($model)
    {
    	$warehouses = Yii::app()->db->createCommand()
    		->select('id, code')->from('warehouses')
    		->queryAll();
    	
    	foreach($warehouses as $wh) {
    		$items = Yii::app()->db->createCommand()
    			->select("iddetail, serialnum, ('${wh['id']}') as idwarehouse, status ")
    			->from("wh".$wh['id'])
    			->where('avail = :p_avail and iditem = :p_iditem', 
    				array(':p_avail'=>'1', ':p_iditem'=>$model->iditemprevious))
    			->queryAll();
    	}
    	
    	foreach($items as & $item) {
    		$item['iddetail'] = idmaker::getCurrentID2();
    		$item['id'] = $model->id;
    		$item['userlog'] = Yii::app()->user->id;
    		$item['datetimelog'] = idmaker::getDateTime();
    	}
    	
    	return $items;
    }
    
    private function append_stockexits($model)
    {
    	$warehouses = Yii::app()->db->createCommand()
	    	->select('id, code')->from('warehouses')
	    	->queryAll();
    	
    	Yii::import('application.modules.stockexits.models.*');
    	foreach($warehouses as $wh) {
    		$items = Yii::app()->db->createCommand()
	    		->select()->from('detailitemmastermods')
	    		->where('id = :p_id and idwarehouse = :p_idwarehouse', 
	    			array(':p_id'=>$model->id, ':p_idwarehouse'=>$wh['id']))
	    		->queryAll();
    		if (count($items)) {
	    		$id = idmaker::getCurrentID2();
	    		$stockexits = new Stockexits();
	    		$stockexits->id = $id;
	    		$stockexits->idatetime = $model->idatetime;
	    		$stockexits->transid = $model->regnum;
	    		$stockexits->regnum = idmaker::getRegNum('AC15');
	    		idmaker::saveRegNum('AC15', $stockexits->regnum);
	    		$stockexits->transname = $this->formid;
	    		$stockexits->transinfo = 'Penggantian Master Barang dan Pemindahan Saldo Stok';
	    		$stockexits->idwarehouse = $wh['id'];
	    		$stockexits->donum = '-';
	    		$stockexits->userlog = Yii::app()->user->id;
	    		$stockexits->datetimelog = idmaker::getDateTime();
	    		$respond = $stockexits->save();
	    		if (!$respond)
	    			throw new CHttpException ( 404, 'Pengeluaran Barang tidak bisa disimpan.');
				foreach($items as $item) {
					$detailstockexits = new Detailstockexits();
					$detailstockexits->iddetail = idmaker::getCurrentID2();
					$detailstockexits->id = $id;
					$detailstockexits->iditem = $model->iditemprevious;
					$detailstockexits->serialnum = $item['serialnum'];
					$detailstockexits->status = $item['status'];
					$detailstockexits->userlog = $item['userlog'];
					$detailstockexits->datetimelog = $item['datetimelog'];
					
					$respond = $detailstockexits->save();
					if (!$respond)
						throw new CHttpException ( 404, 'Detil Pengeluaran Barang tidak bisa disimpan.');
				}
    		}
    	}
    }
      
    private function append_stockentries($model)
    {
    	$warehouses = Yii::app()->db->createCommand()
    	->select('id, code')->from('warehouses')
    	->queryAll();
    	 
    	Yii::import('application.modules.stockentries.models.*');
    	foreach($warehouses as $wh) {
    		$items = Yii::app()->db->createCommand()
	    		->select()->from('detailitemmastermods')
	    		->where('id = :p_id and idwarehouse = :p_idwarehouse',
    				array(':p_id'=>$model->id, ':p_idwarehouse'=>$wh['id']))
    				->queryAll();
    		if (count($items)) {
	    		$stockentries = new Stockentries();
	    		$id = idmaker::getCurrentID2();
	    		$stockentries->id = $id;
	    		$stockentries->idatetime = $model->idatetime;
	    		$stockentries->regnum = idmaker::getRegNum('AC3');
	    		idmaker::saveRegNum('AC3', $stockentries->regnum);
	    		$stockentries->transid = $model->regnum;
	    		$stockentries->transname = $this->formid;
	    		$stockentries->transinfo = 'Penggantian Master Barang dan Pemindahan Saldo Stok';
	    		$stockentries->idwarehouse = $wh['id'];
	    		$stockentries->donum = '-';
	    		$stockentries->userlog = Yii::app()->user->id;
	    		$stockentries->datetimelog = idmaker::getDateTime();
	    		$respond = $stockentries->save();
	    		if (!$respond)
	    			throw new CHttpException ( 404, 'Pemasukan Barang tidak bisa disimpan.'. print_r($stockentries->errors));
	    		foreach($items as $item) {
	    			$detailstockentries = new Detailstockentries();
	    			$detailstockentries->iddetail = idmaker::getCurrentID2();
	    			$detailstockentries->id = $id;
	    			$detailstockentries->iditem = $model->iditemnext;
	    			$detailstockentries->serialnum = $item['serialnum'];
	    			$detailstockentries->status = $item['status'];
	    			$detailstockentries->userlog = $item['userlog'];
	    			$detailstockentries->datetimelog = $item['datetimelog'];
	    
	    			$respond = $detailstockentries->save();
	    			if (!$respond)
	    				throw new CHttpException ( 404, 'Detil Pemasukan Barang tidak bisa disimpan.');
	    			Action::modifyIDItemInWarehouse($wh['id'], $item['serialnum'], 
	    				$model->iditemprevious, $model->iditemnext);
	    		}
	    		
    		}
    	}
    }
    
    private function remove_stockexits($model)
    {
    	$stockexits = Yii::app()->db->createCommand()
    		->select('id, idwarehouse')->from('stockexits')->where('transid = :p_transid', 
    			array(':p_transid'=>$model->regnum))
    		->queryAll();
    	foreach($stockexits as $se) {
    		$serialnums = Yii::app()->db->createCommand()
    			->select('serialnum')->from('detailstockexits')
    			->where('id = :p_id', array(':p_id'=>$se['id']))
    			->queryColumn();
    		foreach($serialnums as $sn) {
    			Action::setItemAvailinWarehouse($se['idwarehouse'], $sn);
    		}
    		Yii::app()->db->createCommand()
    			->delete('stockexits',  'id = :p_id', array(':p_id'=>$se['id']));
    		Yii::app()->db->createCommand()
    			->delete('detailstockexits',  'id = :p_id', array(':p_id'=>$se['id']));			
    	}
    }
    
    private function remove_stockentries($model)
    {
    	$stockexits = Yii::app()->db->createCommand()
    		->select('id, idwarehouse')->from('stockentries')->where('transid = :p_transid',
    			array(':p_transid'=>$model->regnum))
    		->queryAll();
    	foreach($stockexits as $se) {
    		$serialnums = Yii::app()->db->createCommand()
	    		->select('serialnum')->from('detailstockentries')
	    		->where('id = :p_id', array(':p_id'=>$se['id']))
	    		->queryColumn();
    		foreach($serialnums as $sn) {
    			Action::modifyIDItemInWarehouse($se['idwarehouse'], $sn, $model->iditemprevious, 
    				$model->iditemnext);
    		}
    		Yii::app()->db->createCommand()
    			->delete('stockentries',  'id = :p_id', array(':p_id'=>$se['id']));
    		Yii::app()->db->createCommand()
    			->delete('detailstockentries',  'id = :p_id', array(':p_id'=>$se['id']));
    	}
    }
}