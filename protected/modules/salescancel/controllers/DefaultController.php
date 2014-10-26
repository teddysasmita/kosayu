<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC231';
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
            $model = $this->loadModel($id);
            $rawdata=$this->loadSalesDetail($model->invnum);
            $this->render('view',array(
            	'model'=>$model, 'rawdata'=>$rawdata
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

			$this->state='create';
			$this->trackActivity('c');
			$model=new Salescancel;
			$this->afterInsert($model);
                
			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);
            print_r($_POST);            
			if(isset($_POST['Salescancel'])) {
				if(isset($_POST['yt0'])) {
					$model->attributes=$_POST['Salescancel'];
					$this->beforePost($model);
					$respond = $model->save();
					if (!$respond) 
						throw new CHttpException(404,'You have no authorization for this operation.');

					$this->afterPost($model);
					$this->redirect(array('view','id'=>$model->id));
				} else if ($_POST['command'] == 'setInvnum') { 
					$model->attributes=$_POST['Salescancel'];
					$total = $this->loadInvoice($model->invnum);
					$model->totalcash = $total['cash'];
					$model->totalnoncash = $total['noncash'];
					$rawdata = $this->loadSalesDetail($model->invnum);
				}
			}
                  
			if (isset($rawdata))
				$this->render('create',array(
					'model'=>$model, 'rawdata'=>$rawdata
				)); 
			else
				$this->render('create',array(
						'model'=>$model,
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
                
			$this->state='update';
			$this->trackActivity('u');
                
			$model=$this->loadModel($id);
			$this->afterEdit($model);

			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);
			$rawdata = $this->loadSalesDetail($model->invnum);
			if(isset($_POST['Salescancel']))
			{
				$oldinvnum = $model->invnum;
				if(isset($_POST['yt0'])) {    
                	$this->beforePost($model);    
                	$model->attributes=$_POST['Salescancel'];
					$this->tracker->modify('salescancel', $id);
					if($model->save()) {
						$this->afterPost($model);
						$this->redirect(array('view','id'=>$model->id));
					}
                } else if ($_POST['command'] == 'setInvnum') {
                	$total = $this->loadInvoice($model->invnum);
                	$model->totalcash = $total['cash'];
                	$model->totalnoncash = $total['noncash'];
                	$rawdata = $this->loadSalesDetail($model->invnum);
                }
			}

			if (isset($rawdata))
				$this->render('update',array(
					'model'=>$model, 'rawdata'=>$rawdata
				)); 
			else 
				$this->render('update',array('model'=>$model));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
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
                $this->tracker->delete('salescancel', $id);
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
                
                $dataProvider=new CActiveDataProvider('Salescancel', array(
                		'criteria'=>array(
                				'order'=>'id desc'
                		))
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
            if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
                Yii::app()->user->id)) {
                $this->trackActivity('s');
                
                $model=new Salescancel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Salescancel']))
			$model->attributes=$_GET['Salescancel'];

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
                $this->tracker->restore('salescancel', $idtrack);
                $dataProvider=new CActiveDataProvider('Salescancel');
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
                
                $this->tracker->restoreDeleted('salescancel', "idtrack", $idtrack);
                
                $dataProvider=new CActiveDataProvider('Salescancel');
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
	 * @return Salescancel the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Salescancel::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Salescancel $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='salescancel-form')
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
        	if($this->state == 'create')
        		idmaker::saveRegnum($this->formid, substr($model->regnum, 2));
        	Action::setInvStatus($model->invnum, '0');
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            if ($this->state == 'create') {
            	$model->regnum="FB".$idmaker->getRegnum($this->formid);
            } else if ($this->state == 'update') {
            	Action::setInvStatus($model->invnum, '1');
            }
        }
        
        protected function beforeDelete(& $model)
        {
            
        }
        
        protected function afterDelete($invnum)
        {
			Action::setInvStatus($invnum, '1');
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
        
        protected function loadSalesDetail($invnum)
        {
        	 return Yii::app()->db->createCommand()
        		->select('b.iddetail, b.iditem, b.qty, b.price, b.discount')
        		->from('salespos a')->join('detailsalespos b', 'b.id = a.id')
        		->where('a.regnum = :p_invnum', array(':p_invnum'=>$invnum))
        		->queryAll();
        }
        
        public function actionPrintcancel($id)
        {
        	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
        			Yii::app()->user->id)) {
        		$this->trackActivity('p');
        
        		$model=$this->loadModel($id);
        		$detailmodel=$this->loadSalesDetail($model->invnum);
        		Yii::import('application.vendors.tcpdf.*');
        		require_once ('tcpdf.php');
        		Yii::import('application.modules.salescancel.components.*');
        		require_once('printcancel.php');
        		ob_clean();
        
        		execute($model, $detailmodel);
        	} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	}
        }
}
