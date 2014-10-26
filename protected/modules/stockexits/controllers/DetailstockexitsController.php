<?php

class DetailstockexitsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC3a';
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
            if(($model==NULL)&&isset(Yii::app()->session['Detailstockexits'])) {
                $model=new Detailstockexits;
                $model->attributes=$this->loadSession($iddetail);
            }  
            $this->render('view',array(
				'model'=>$model,
			));
		} else {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id)
	{
             if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                    Yii::app()->user->id))  {   
                $this->state='c';
                $this->trackActivity('c');    
                    
                $model=new Detailstockexits;
                $this->afterInsert($id, $model);
                
                $master=Yii::app()->session['master'];
                                
				// Uncomment the following line if AJAX validation is needed
				$this->performAjaxValidation($model);
                
                if(isset($_POST['Detailstockexits'])) {
                    $temp=Yii::app()->session['Detailstockexits'];
                    $model->attributes=$_POST['Detailstockexits'];
                    //posting into session
                    $temp[]=$_POST['Detailstockexits'];
                    
                    if ($model->validate()) {
                        Yii::app()->session['Detailstockexits']=$temp;
                        if ($master=='create')
                            $this->redirect(array('default/createdetail'));
                        else if($master=='update')
                            $this->redirect(array('default/updatedetail'));
                    }    
                }                

                $this->render('create',array(
                    'model'=>$model, 'master'=>$master
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
	public function actionUpdate($iddetail, $idwh, $transname, $transid)
	{
             if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                    Yii::app()->user->id))  {
                
                $this->state='u';
                $this->trackActivity('u');
                $error = '';
                $master=Yii::app()->session['master'];
                
                $model=$this->loadModel($iddetail);
                if(isset(Yii::app()->session['Detailstockexits'])) {
                    $model=new Detailstockexits;
                    $model->attributes=$this->loadSession($iddetail);
                }
                $this->afterEdit($model);
                    
                // Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);
                
               if(isset($_POST['Detailstockexits'])) {
                  $temp=Yii::app()->session['Detailstockexits'];
                  $model->attributes=$_POST['Detailstockexits'];
                  foreach ($temp as $tk=>$tv) {
                     if($tv['iddetail']==$_POST['Detailstockexits']['iddetail']) {
                         $temp[$tk]=$_POST['Detailstockexits'];
                         break;
                     }
                  }
                  if ($transname == 'AC25') {
                  	$respond = $this->checkSendRepair($transid, $model->iditem, $model->serialnum);
                  	if (!$respond) {
						$error = 'Nomor seri keliru';
                  	}
                  } else 
                  	$respond = TRUE;
                    //posting into session
                  if ($respond) {
                  	if($model->validate()) {
						Yii::app()->session['Detailstockexits']=$temp;

                     	if ($master=='create')
                           $this->redirect(array('default/createdetail'));
                     	else if($master=='update')
                           $this->redirect(array('default/updatedetail'));
                  	}
                  }	
                }
               
                $this->render('update',array(
                        'model'=>$model,'master'=>$master, 'idwh'=>$idwh, 'error'=>$error
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
                
                $details=Yii::app()->session['Detailstockexits'];
                foreach ($details as $ik => $iv) {
                   if($iv['iddetail']==$iddetail) {
                      if(isset(Yii::app()->session['Deletedetailstockexits']))
                         $deletelist=Yii::app()->session['Deletedetailstockexits'];
                      $deletelist[]=$iv;
                      Yii::app()->session['Deletedetailstockexits']=$deletelist;
                      unset($details[$ik]);
                      break;
                   }
                }
                
                            
                Yii::app()->session['Detailstockexits']=$details;

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
                   'model'=>$model,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }   
        }
        
        public function actionDeleted($id)
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
                $this->tracker->restore('detailstockexits', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Detailstockexits');
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
                $this->tracker->restoreDeleted('detailstockexits', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Detailstockexits');
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
	 * @return Detailstockexits the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Detailstockexits::model()->findByPk($id);
		/*if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		*/
                return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Detailstockexits $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='detailstockexits-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        protected function loadSession($iddetail)
        {
            $details=Yii::app()->session['Detailstockexits'];
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
            $model->status = '0';
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
        
        private function checkSendRepair($regnum, $iditem, $serialnum) 
        {
        	$id = Yii::app()->db->createCommand()->select('id')->from('sendrepairs')
        		->where('regnum = :p_regnum', array(':p_regnum'=>$regnum))
        		->queryScalar();
        	 
        	$item = Yii::app()->db->createCommand()->select()
				->from('detailsendrepairs')
				->where('id = :p_id and iditem = :p_iditem and serialnum = :p_serialnum',
        				array(':p_id'=>$id, ':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum))
        		->queryAll();
        	
        	if (!$item)
        		return FALSE;
        	else 
        		return TRUE;
        }
}
