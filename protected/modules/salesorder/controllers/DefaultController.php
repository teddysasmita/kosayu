<?php

class DefaultController extends Controller
{
      	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC1';
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
                $this->state='c';
                $this->trackActivity('c'); 
                
                $model=new Salesorders;
                $this->afterInsert($model);
                 
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Salesorders'])) {
                   Yii::app()->session['Salesorders']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Salesorders'];
                }

                // Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);

                if (isset($_POST)){
                   if(isset($_POST['yt0']))
                   {
                      //The user pressed the button;
                      $model->attributes=$_POST['Salesorders'];
                      
                      if(isset(Yii::app()->session['Detailsalesorders']))
                        $details=Yii::app()->session['Detailsalesorders'];

                      $this->beforePost($model);
                      $respond=$model->save();
                      if($respond) {
                          $this->afterPost($model);
                      } else {
                          throw new CHttpException(404,'There is an error in master posting');
                      }
                      
                      if (isset($details)) {
                          $respond=$respond&&$this->saveNewDetails($details);
                      }    
                      if($respond) {
                         Yii::app()->session->remove('Salesorders');
                         Yii::app()->session->remove('Detailsalesorders');
                         $this->redirect(array('view','id'=>$model->id));
                      }

                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Salesorders'];
                         Yii::app()->session['Salesorders']=$_POST['Salesorders'];
                         $this->redirect(array('detailsalesorders/create','id'=>$model->id));
                      }
                   }
                }

                $this->render('create',array(
                    'model'=>$model,
                ));
            }  else {
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
                
                if(!isset(Yii::app()->session['Salesorders']))
                   Yii::app()->session['Salesorders']=$model->attributes;
                else
                   $model->attributes=Yii::app()->session['Salesorders'];
                
                if(!isset(Yii::app()->session['Detailsalesorders'])) 
                    Yii::app()->session['Detailsalesorders']=$this->loadDetails($id);
                    
                // Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);

                if(isset($_POST)) {
                    if(isset($_POST['yt0'])) {
                        $model->attributes=$_POST['Salesorders'];
                        
                        $this->beforePost($model);
                        $this->tracker->modify('salesorders', $id);
                        $respond=$model->save();
                        if($respond) {
                          $this->afterPost($model);
                        } else {
                          throw new CHttpException(404,'There is an error in master posting');
                        }
                        
                        if(isset(Yii::app()->session['Detailsalesorders']))
                            $details=Yii::app()->session['Detailsalesorders'];
                        if(isset($details)) {
                            $respond=$respond&&$this->saveDetails($details);
                            if($respond) {
                            
                            } else {
                              throw new CHttpException(404,'There is an error in detail posting');
                            }
                        };
                        
                        if(isset(Yii::app()->session['Deletedetailsalesorders']))
                            $deletedetails=Yii::app()->session['Deletedetailsalesorders'];
                        if(isset($deletedetails)) {
                            $respond=$respond&&$this->deleteDetails($deletedetails);
                            if($respond) {
                            
                            } else {
                              throw new CHttpException(404,'There is an error in detail deletion');
                            }
                        };
                                                
                        if($respond) {
                            Yii::app()->session->remove('Salesorders');
                            Yii::app()->session->remove('Detailsalesorders');
                            Yii::app()->session->remove('Deletedetailsalesorders');
                            $this->redirect(array('view','id'=>$model->id));
                        }
                    }
                }

                $this->render('update',array(
                        'model'=>$model,
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
	public function actionDelete($id)
	{
            if(Yii::app()->authManager->checkAccess($this->formid.'-Delete', 
                    Yii::app()->user->id))  {
                $this->trackActivity('d');
                $model=$this->loadModel($id);
                $this->beforeDelete($model);
                $this->tracker->delete('salesorders', $id);
                
                $detailmodels=Detailsalesorders::model()->findAll('id=:id',array(':id'=>$id));
                foreach($detailmodels as $dm) {
                   $this->tracker->init();
                    $this->tracker->delete('detailsalesorders', array('iddetail'=>$dm->iddetail));
                    $dm->delete();
                }
                $model->delete();
                $this->afterDelete();
                
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
            Yii::app()->session->remove('Salesorders');
            Yii::app()->session->remove('Detailsalesorders');
            Yii::app()->session->remove('Detailsalesorders2');
            Yii::app()->session->remove('DeleteDetailsalesorders');

            $dataProvider=new CActiveDataProvider('Salesorders',
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
            if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
                Yii::app()->user->id)) {
                $this->trackActivity('s');
                
        	$model=new Salesorders('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Salesorders']))
			$model->attributes=$_GET['Salesorders'];

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
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Salesorders the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Salesorders::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Salesorders $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='salesorders-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
      
      
        public function actionCreateDetail()
        {
         //this action continues the process from the detail page
            if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                    Yii::app()->user->id))  {
                $model=new Salesorders;
                $model->attributes=Yii::app()->session['Salesorders'];

                $details=Yii::app()->session['Detailsalesorders'];
                $this->afterInsertDetail($model, $details);

                $this->render('create',array(
                    'model'=>$model,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            } 
          }
      
        public function actionUpdateDetail()
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                    Yii::app()->user->id))  {
            
                $model=new Salesorders;
                $model->attributes=Yii::app()->session['Salesorders'];

                $details=Yii::app()->session['Detailsalesorders'];
                $this->afterUpdateDetail($model, $details);

                $this->render('update',array(
                    'model'=>$model,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
      
        public function actionDeleteDetail()
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                    Yii::app()->user->id))  {
            
                
                $model=new Salesorders;
                $model->attributes=Yii::app()->session['Salesorders'];

                $details=Yii::app()->session['Detailsalesorders'];
                $this->afterDeleteDetail($model, $details);

                $this->render('update',array(
                    'model'=>$model,
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
                $this->tracker->restoreDeleted('salesorders', $idtrack);
                $this->tracker->restoreDeleted('detailsalesorders', $idtrack);
                $dataProvider=new CActiveDataProvider('Salesorders');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
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
                $this->tracker->restore('salesorders', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Salesorders');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
        
        protected function saveNewDetails(array $details)
        {                  
            foreach ($details as $row) {
                $detailmodel=new Detailsalesorders;
                $detailmodel->attributes=$row;
                $respond=$detailmodel->insert();
                if (!$respond) {
                   break;
                }
            }
            return $respond;
        }
      
        protected function saveDetails(array $details)
        {
            $idmaker=new idmaker();
            
            foreach ($details as $row) {
                $detailmodel=Detailsalesorders::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                    $detailmodel=new Detailsalesorders;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('detailsalesorders', array('iddetail'=>$detailmodel->iddetail));
                    }    
                }
                $detailmodel->attributes=$row;
                $detailmodel->userlog=Yii::app()->user->id;
                $detailmodel->datetimelog=$idmaker->getDateTime();
                $respond=$detailmodel->save();
                if (!$respond) {
                  break;
                }
             }
             return $respond;
        }
      
        protected function deleteDetails(array $details)
        {
            $respond=true;
            foreach ($details as $row) {
                $detailmodel=Detailsalesorders::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', $this->detailformid);
                    $this->tracker->delete('detailsalesorders', $detailmodel->id);
                    $respond=$detailmodel->delete();
                    if (!$respond) {
                      break;
                    }
                }
            }
            return $respond;
        }


        protected function loadDetails($id)
        {
            $sql="select * from detailsalesorders where id='$id'";
            $details=Yii::app()->db->createCommand($sql)->queryAll();

            return $details;
        }

        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();
            $model->id=$idmaker->getCurrentID2();
            $model->idatetime=$idmaker->getDateTime();
            $model->regnum=$idmaker->getRegNum($this->formid);
            $lookup=new lookup();
            $model->status=$lookup->reverseOrderStatus('Belum Diproses');
        }

        protected function afterInsertDetail(& $model, $details)
        {
         $total=0;
         $totaldisc=0;
         foreach ($details as $row) {
            $total=$total+$row['price']*$row['qty'];
            $totaldisc=$totaldisc+$row['discount']*$row['qty'];
         }
         $model->attributes=Yii::app()->session['Salesorders'];
         $model->total=$total;
         $model->discount=$totaldisc;
        }

        protected function afterUpdateDetail(& $model, $details)
        {
         $total=0;
         $totaldisc=0;
         foreach ($details as $row) {
            $total=$total+$row['price']*$row['qty'];
            $totaldisc=$totaldisc+$row['discount']*$row['qty'];
         }
         $model->attributes=Yii::app()->session['Salesorders'];
         $model->total=$total;
         $model->discount=$totaldisc;      
        }
        
        protected function afterDeleteDetail(& $model, $details)
        {
         $total=0;
         $totaldisc=0;
         foreach ($details as $row) {
            $total=$total+$row['price']*$row['qty'];
            $totaldisc=$totaldisc+$row['discount']*$row['qty'];
         }
         $model->attributes=Yii::app()->session['Salesorders'];
         $model->total=$total;
         $model->discount=$totaldisc;      
        }
      
        protected function afterPost(& $model)
        {
            $idmaker=new idmaker();
            $idmaker->saveRegNum($this->formid, $model->regnum);             
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            $model->regnum=$idmaker->getRegNum($this->formid);
        }
        
        protected function beforeDelete(& $model)
        {
            
        }
        
        protected function afterDelete()
        {
               
        }
        
        protected function afterEdit(& $model)
        {
            
        }
        
        protected function trackActivity($action, $formid=NULL)
        {
            $this->tracker=new Tracker();
            $this->tracker->init();
            if($formid)
                $this->tracker->logActivity($formid, $action);
            else
                $this->tracker->logActivity($this->formid, $action);
        }
}
