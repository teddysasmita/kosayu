<?php

class DetailitemtransfersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC18a';
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
	public function actionView($iddetail)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
            $this->trackActivity('v');
            $model=$this->loadModel($iddetail);
            if(($model==NULL)&&isset(Yii::app()->session['Detailitemtransfers'])) {
                $model=new Detailitemtransfers;
                $model->attributes=$this->loadSession($iddetail);
            }  
            $this->render('view',array(
				'model'=>$this->loadModel($iddetail), 
			));
		} else {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id, $idwhsource)
	{
             if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                    Yii::app()->user->id))  {   
                $this->state='c';
                $this->trackActivity('c');    
                    
                $model=new Detailitemtransfers;
                $this->afterInsert($id, $model);
                
                $error = '';
                $master=Yii::app()->session['master'];
                                
		// Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);
                
                if(isset($_POST['yt0'])) {
                    $temp=Yii::app()->session['Detailitemtransfers'];
                    $model->attributes=$_POST['Detailitemtransfers'];
                    if (Action::checkItemQty($model->iditem, $idwhsource) >= $model->qty) {
                    //posting into session
	                    $temp[]=$_POST['Detailitemtransfers'];
	                    
	                    if ($model->validate()) {
	                        Yii::app()->session['Detailitemtransfers']=$temp;
	                        if ($master=='create')
	                            $this->redirect(array('default/createdetail'));
	                        else if($master=='update')
	                            $this->redirect(array('default/updatedetail'));
	                    }    
                    } else {
                    	$error = 'Jumlah barang tidak cukup di gudang tersebut';
                    }
                }                

                $this->render('create',array(
                    'model'=>$model, 'master'=>$master, 'error'=>$error
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
	public function actionUpdate($iddetail, $idwhsource)
	{
             if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                    Yii::app()->user-> id))  {
                
                $this->state='u';
                $this->trackActivity('u');
                
                $master=Yii::app()->session['master'];
                $error = '';
                
                $model=$this->loadModel($iddetail);
                if(isset(Yii::app()->session['Detailitemtransfers'])) {
                    $model=new Detailitemtransfers;
                    $model->attributes=$this->loadSession($iddetail);
                }
                $this->afterEdit($model);
                    
                // Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);
                
                if(isset($_POST['yt0'])) {
                    $temp=Yii::app()->session['Detailitemtransfers'];
                    $model->attributes=$_POST['Detailitemtransfers'];
                    if (Action::checkItemQty($model->iditem, $idwhsource) >= $model->qty) {
	                    foreach ($temp as $tk=>$tv) {
	                        if($tv['iddetail']==$_POST['Detailitemtransfers']['iddetail']) {
	                            $temp[$tk]=$_POST['Detailitemtransfers'];
	                            break;
	                        }
	                    }
	                    //posting into session
			    		if($model->validate()) {
	                    	Yii::app()->session['Detailitemtransfers']=$temp;
				
	                    	if ($master=='create')
	                        	$this->redirect(array('default/createdetail'));
	                    	else if($master=='update')
	                        	$this->redirect(array('default/updatedetail'));
			    		}
                    } else {
                    	$error = 'Jumlah barang tidak cukup di gudang tersebut';
                    }	
                }
               
                $this->render('update',array(
                        'model'=>$model,'master'=>$master, 'error'=>$error
                ));
            }  else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($iddetail)
	{
            if(Yii::app()->authManager->checkAccess($this->formid.'-Delete', 
                    Yii::app()->user->id))  {
                
                $this->trackActivity('d');
                
                $details=Yii::app()->session['Detailitemtransfers'];
                foreach ($details as $ik => $iv) {
                   if($iv['iddetail']==$iddetail) {
                      if(isset(Yii::app()->session['Deletedetailitemtransfers']))
                         $deletelist=Yii::app()->session['Deletedetailitemtransfers'];
                      $deletelist[]=$iv;
                      Yii::app()->session['Deletedetailitemtransfers']=$deletelist;
                      unset($details[$ik]);
                      break;
                   }
                }
                
                            
                Yii::app()->session['Detailitemtransfers']=$details;

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }

	public function actionHistory($iddetail)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $model=$this->loadModel($iddetail);
                $this->render('history', array(
                   'model'=>$model
                    
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
                    'id'=>$id,
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
                $this->tracker->restore('detailitemtransfers', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Detailitemtransfers');
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
                $this->tracker->restoreDeleted('detailitemtransfers', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Detailitemtransfers');
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
	 * @return Detailitemtransfers the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Detailitemtransfers::model()->findByPk($id);
		/*if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		*/
                return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Detailitemtransfers $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='detailitemtransfers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        protected function loadSession($iddetail)
        {
            $details=Yii::app()->session['Detailitemtransfers'];
            foreach ($details as $row) {
                if($row['iddetail']==$iddetail)
                    return $row;
            }
            throw new CHttpException(404,'The requested page does not exist.');
        }
        
        
        protected function afterInsert($id, & $model)
        {
            $idmaker=new idmaker();
            $model->id=$id;  
            $model->iddetail=$idmaker->getCurrentID2();
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
        }
        
        protected function afterPost(& $model)
        {
            
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
        }
        
        protected function beforeDelete(& $model)
        {
            
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
}
