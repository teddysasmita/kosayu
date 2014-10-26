<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC24';
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
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
        
        /*
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        */    
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

			$this->state='c';
			$this->trackActivity('c');
			$model=new Salesreplace;
			$this->afterInsert($model);
                
			Yii::app()->session['master']='create';
			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);
                        
			if(isset($_POST['Salesreplace'])) {
				$model->attributes=$_POST['Salesreplace'];
				if(isset($_POST['yt0'])) {
					$this->beforePost($model);
					if($model->save()) {
						$this->afterPost($model);
						$this->redirect(array('view','id'=>$model->id));
					};
				} else if ($_POST['command'] == 'setInvnum') { 
					$total = $this->loadInvoice($model->invnum);
					$model->totalcash = $total['cash'];
					$model->totalnoncash = $total['noncash'];
					Yii::app()->session['Detailsalesreplace']=$this->loadDetails($model->invnum,
						$model->id);
				}
			}
			$this->render('create',array(
				'model'=>$model
			));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
			Yii::app()->user->id))  {
                
			$this->state='u';
			$this->trackActivity('u');
                
			$model=$this->loadModel($id);
			$this->afterEdit($model);
			Yii::app()->session['master']='update';
			
			//as the operator enter for the first time, we load the default value to the session
			if (!isset(Yii::app()->session['Salesreplace'])) {
				Yii::app()->session['Salesreplace']=$model->attributes;
			} else {
				// use the session to fill the model
				$model->attributes=Yii::app()->session['Salesreplace'];
			}
			
			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST['Salesreplace']))
			{
				$oldinvnum = $model->invnum;
				if(isset($_POST['yt0'])) {
                      //The user pressed the button;
					$model->attributes=$_POST['Purchasesorders'];
                      
                      $this->beforePost($model);
                      $respond=$model->save();
                      if($respond) {
                          $this->afterPost($model);
                      } else {
                          throw new CHttpException(404,'There is an error in master posting');
                      }
                      
                      if(isset(Yii::app()->session['Detailpurchasesorders']) ) {
                        $details=Yii::app()->session['Detailpurchasesorders'];
                        $respond=$respond&&$this->saveNewDetails($details);
                      } 
                      
                      if(isset(Yii::app()->session['Detailpurchasesorders2']) ) {
                        $details=Yii::app()->session['Detailpurchasesorders2'];
                        $respond=$respond&&$this->saveNewDetails2($details);
                      }
                      
                      if($respond) {
                         Yii::app()->session->remove('Purchasesorders');
                         Yii::app()->session->remove('Detailpurchasesorders');
                         $this->redirect(array('view','id'=>$model->id));
                      }
				$model->attributes=$_POST['Salesreplace'];
				if(isset($_POST['yt0'])) {    
                	$this->beforePost($model);    
					$this->tracker->modify('salesreplace', $id);
					if($model->save()) {
						$this->afterPost($model);
						if($oldinvnum !== $model->invnum)
							$this->setInvStatus($oldinvnum, '1');
						$this->redirect(array('view','id'=>$model->id));
					}
                } else if ($_POST['command'] == 'setInvnum') {
                	$model->total = $this->loadInvoice($model->invnum);
                }
			}

			$this->render('update',array('model'=>$model));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
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
                $this->trackActivity('d');
                $model=$this->loadModel($id);
                $oldinvnum = $model->invnum;
                $this->beforeDelete($model);
                $this->tracker->delete('salesreplace', $id);
                $model->delete();
                $this->afterDelete($oldinvnum);    
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
                
                $dataProvider=new CActiveDataProvider('Salesreplace');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
                Yii::app()->session->remove('Salesreplace');
                Yii::app()->session->remove('Detailsalesreplace');
                Yii::app()->session->remove('DeleteDetailsalesreplace');
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
	}

	/**
	 * Manages all models.
	 */
		public function actionAdmin()
		{
	            if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
	                Yii::app()->user->id)) {
	                $this->trackActivity('s');
	                
	                $model=new Salesreplace('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Salesreplace']))
				$model->attributes=$_GET['Salesreplace'];
	
			$this->render('admin',array(
				'model'=>$model,
			));
	            } else {
	                throw new CHttpException(404,'You have no authorization for this operation.');
	            }
		}
        
        public function actionHistory($id)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $model=$this->loadModel($id);
                $this->render('history', array(
                   'model'=>$model,
                    
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }   
        }
        
        public function actionDeleted()
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->render('deleted', array(
         
                    
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }   
        }
        
        public function actionRestore($idtrack)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->trackActivity('r');
                $this->tracker->restore('salesreplace', $idtrack);
                $dataProvider=new CActiveDataProvider('Salesreplace');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
        
        public function actionRestoreDeleted($idtrack)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->trackActivity('n');
                $this->tracker->restoreDeleted('salesreplace', $idtrack);
                $dataProvider=new CActiveDataProvider('Salesreplace');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
        

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Salesreplace the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Salesreplace::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Salesreplace $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='salesreplace-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
    }
        
        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();
            $model->id=$idmaker->getcurrentID2();  
            $model->idatetime = $idmaker->getDateTime();
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$model->idatetime;    
        }
        
        protected function afterPost(& $model)
        {
        	idmaker::saveRegnum($this->formid, $model->regnum);
        	$this->setInvStatus($model->invnum, '0');
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            $model->regnum=$idmaker->getRegnum($this->formid);
        }
        
        protected function beforeDelete(& $model)
        {
            
        }
        
        protected function afterDelete($invnum)
        {
			$this->setInvStatus($invnum, '1');
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
        
        protected function loadInvoice($invnum)
        {
        	$id=Yii::app()->db->createCommand()
        		->select('id')->from('salespos')
        		->where('status=:p_status and regnum=:p_regnum',
                	array(':p_status'=>'1', ':p_regnum'=>$invnum))
                ->queryScalar();
        	if ($id !== FALSE) {
        		$salesCash=Yii::app()->db->createCommand()
        			->select('(cash-cashreturn) as total')
        			->from('salespos')
        			->where('regnum = :p_regnum',
        				array(':p_regnum'=>$invnum))
        			->queryScalar();
        		//echo $salesCash. ' ';
        		if (!$salesCash)
        			$salesCash = 0;
        		$salesNonCash=Yii::app()->db->createCommand()
        			->select('sum(b.amount + b.surcharge) as total')
        			->from('salespos a')
        			->join('posreceipts b', "b.idpos = a.id")
        			->where('a.regnum = :p_regnum',
        				array(':p_regnum'=>$invnum))
        			->queryScalar();
        		//echo $salesNonCash. ' ';
        		if (!$salesNonCash)
        			$salesNonCash = 0;
        		$receivableCash=Yii::app()->db->createCommand()
        			->select('(a.cash-a.cashreturn) as total')
        			->from('receivablespos a')
        			->where('a.invnum = :p_regnum',
        				array(':p_regnum'=>$invnum))
        			->queryScalar();
        		//echo $receivableCash. ' ';
        		if (!$receivableCash)
        			$receivableCash = 0;
        		$receivableNonCash=Yii::app()->db->createCommand()
        			->select('sum(b.amount+b.surcharge) as total')
        			->from('receivablespos a')
        			->join('posreceipts b', "b.idpos = a.id")
        			->where('a.regnum = :p_regnum',
        				array(':p_regnum'=>$invnum))
        			->querySCalar();
        		//echo $receivableNonCash. ' ';
        		if (!$receivableNonCash)
        			$receivableNonCash = 0;
        		$total['cash'] = $salesCash+$receivableCash;
        		$total['noncash'] = $salesNonCash+$receivableNonCash;
        		//echo $total;
        		return $total;
        	} else
        	return array('cash'=>0, 'noncash'=>0);
        }	
        
        public static function setInvStatus($invnum, $status)
        {
 			$sql="update salespos set status = :p_status where regnum = :p_regnum ";
        	$stmt=Yii::app()->db->createCommand($sql)
        		->execute(array(':p_regnum'=>$invnum, ':p_status'=>$status));
        }
        
        protected function loadDetails($invnum, $id)
        {
        	$salesdetails=Yii::app()->db->createCommand()->select('b.*')
        		->from('salespos a')->join('detailsalespos b', 'b.id = a.id')
        		->where('a.regnum = :p_regnum', array(':p_regnum'=>$invnum))
        		->queryAll();
        	foreach($salesdetails as $detail) {
        		$canceldetail['id']=$id;
        		$canceldetail['iddetail']=idmaker::getCurrentID();
        		$canceldetail['iditem']=$detail['iditem'];
        		$canceldetail['qty']=$detail['qty'];
        		$canceldetail['price']=$detail['price'];
        		$canceldetail['deleted']='0';
        		$canceldetail['iditemnew']=$detail['iditem'];
        		$canceldetail['qtynew']=$detail['qty'];
        		$canceldetail['pricenew']=$detail['price'];
        		$canceldetail['userlog']=Yii::app()->user->id;
        		$canceldetail['datetimelog']=idmaker::getDateTime();
        		
        		$canceldetails[]=$canceldetail;
        	};
        	return $canceldetails;       
        }
        
        public function actionCreatedetail()
        {
        	//this action continues the process from the detail page
        	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
        			Yii::app()->user->id))  {
        		$model=new Salesreplace;
        		$model->attributes=Yii::app()->session['Salesreplace'];
        
        		$details=Yii::app()->session['Detailsalesreplace'];
        		$this->afterInsertDetail($model, $details);
        
        		$this->render('create',array(
        				'model'=>$model,
        		));
        	} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	}
        }
        
        protected function afterInsertDetail(& $model, $details)
        {
        	$this->sumDetail($model, $details);
        }
        
        private function sumDetail(& $model, $details)
        {
        	/*$total=0;
        	$totaldisc=0;
        	foreach ($details as $row) {
        		$total=$total+(($row['price'])*$row['qty']);
        		$totaldisc=$totaldisc+$row['discount']*$row['qty'];
        	}
        	$model->attributes=Yii::app()->session['Purchasesorders'];
        	$model->total=$total;
        	$model->discount=$totaldisc;*/
        }
}
