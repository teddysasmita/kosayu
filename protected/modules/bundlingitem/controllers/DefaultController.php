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
            $this->trackActivity('v');
            $this->render('view',array(
                    'model'=>$this->loadModel($id),
            ));
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
                
                $model=new Items;
                
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we initiate the default values
                if (!isset(Yii::app()->session['Items'])) {
                   $this->afterInsert($model);
                   // load the model to the session
                   Yii::app()->session['Items']=$model->attributes;
                } else {
                   // load the session to the model when the session is available 
                   $model->attributes=Yii::app()->session['Items'];
                }

                // Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);

                // When user press the button
                if (isset($_POST)){
                   if(isset($_POST['yt0']))
                   {
                      //Retrieve the values from master and all details were in the session
                      $model->attributes=$_POST['Items'];
                      if (isset(Yii::app()->session['Detailitems']))
                        $details=Yii::app()->session['Detailitems'];
                      
                      //commit the master table
                      $this->beforePost($model);  
                      $respond=$model->save();
                      
                      if($respond){
                        $this->afterPost($model);  
                      } else {
                         throw new CHttpException(404,'There is an error in master posting');
                      }
                      
                      //commit the detail table
                      if(isset(Yii::app()->session['Detailitems'])) {
                         $respond=$respond&&$this->saveNewDetails ($details);
                          if($respond) {
                             Yii::app()->session->remove('Items');
                             if(isset(Yii::app()->session['Detailitems']))
                                Yii::app()->session->remove('Detailitems');
                             $this->redirect(array('view','id'=>$model->id));
                          } else {
                             throw new CHttpException(404,'There is an error in detail posting');
                          }
                      }
                   } // for any changes on input form
                   else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Items'];
                         Yii::app()->session['Items']=$_POST['Items'];
                         $this->redirect(array('detailitems/create','id'=>$model->id));
                      } else if($_POST['command']=='setitemtype') {
                         $model->attributes=$_POST['Items'];
                         Yii::app()->session['Items']=$_POST['Items'];
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
                
                // loading master and detail data into session
                if (!isset(Yii::app()->session['Items'])) {
                   Yii::app()->session['Items']=$model->attributes; 
                };
                
                if (!isset(Yii::app()->session['Detailitems'])) {
                   Yii::app()->session['Detailitems']=$this->loadDetails($id);
                }

                    // Uncomment the following line if AJAX validation is needed
                    // $this->performAjaxValidation($model);

                if(isset($_POST)) {
                   if(isset($_POST['yt0'])) {
                      $model->attributes=$_POST['Items'];

                      if(isset(Yii::app()->session['Detailitems']))
                         $details=Yii::app()->session['Detailitems'];

                      // retrieving any deleted detail data
                      if (isset(Yii::app()->session['Deletedetailitems'])) {
                         $deletedetails=Yii::app()->session['Deletedetailitems'];
                         $respond=$this->deleteDetails($deletedetails);
                      }
                      
                      $this->beforePost($model);
                      $this->tracker->modify('items', $id);
                      // commit the master table
                      $respond=$respond&&$model->save();
                      if($respond){
                          $this->afterPost($model);
                      } else {   
                         throw new CHttpException(404,'There is an error in master posting');
                      };   
                      // commit the detail tables
                      if(isset($details)) {
                         $respond=$respond&&$this->saveDetails ($details);
                         if($respond) {
                             Yii::app()->session->remove('Items');
                             Yii::app()->session->remove('Detailitems');
                             Yii::app()->session->remove('Deletedetailitems');
                             $this->redirect(array('view','id'=>$model->id));
                          } else {
                              throw new CHttpException(404,'There is an error in master posting');
                          }
                      }
                   }
                }

                $this->render('update',array(
                        'model'=>$model,
                ));
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
                $this->beforeDelete($model);
                $this->tracker->delete('items', $id);
                
		$model=$this->loadModel($id)->delete();
                $this->afterDelete($model);

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
         if(isset(Yii::app()->session['Items']))
            Yii::app()->session->remove('Items');
         if(isset(Yii::app()->session['Detailitems']))
            Yii::app()->session->remove('Detailitems');
         $dataProvider=new CActiveDataProvider('Items');
         $this->render('index',array(
		'dataProvider'=>$dataProvider,
         ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        	$model=new Items('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Items']))
			$model->attributes=$_GET['Items'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Items the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Items::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Items $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='items-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
      
      
      public function actionCreateDetail()
      {
         //this action continues the process from the detail page
         $model=new Items;
         $model->attributes=Yii::app()->session['Items'];
         
         $details=Yii::app()->session['Detailitems'];
         $this->onInsertDetail($model, $details);
         		
         $this->render('create',array(
		'model'=>$model,
         ));
      }
      
      public function actionUpdateDetail()
      {
         $model=new Items;
         $model->attributes=Yii::app()->session['Items'];
         
         $details=Yii::app()->session['Detailitems'];
         $this->onUpdateDetail($model, $details);
            
         $this->render('update',array(
		'model'=>$model,
         ));
      }
      
      public function actionDeleteDetail()
      {
         $model=new Items;
         $model->attributes=Yii::app()->session['Items'];
         
         $details=Yii::app()->session['Detailitems'];
         $this->onUpdateDetail($model, $details);
            
         $this->render('update',array(
		'model'=>$model,
         ));
      }
     
      protected function saveNewDetails(array $details)
      {
         $respond=true;
                  
         foreach ($details as $row) {
            $detailmodel=new Detailitems;
            $detailmodel->attributes=$row;
            $respond=$respond&&$detailmodel->insert();
            if (!$respond) {
               break;
            }
         }
         return $respond;
      }
      
      protected function saveDetails(array $details)
      {
         $respond=true;
            
         foreach ($details as $row) {
            $detailmodel=Detailitems::model()->findByPk($row['iddetail']);
            if($detailmodel==NULL)
               $detailmodel=new Detailitems;
            $detailmodel->attributes=$row;
            $respond=$respond&&$detailmodel->save();
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
            $detailmodel=Detailitems::model()->findByPk($row['iddetail']);
            $respond=$respond&&$detailmodel->delete();
            if (!$respond) {
              break;
            }
         }
         return $respond;
      }
      
      
      protected function loadDetails($id)
      {
         $sql="select * from detailitems where id='$id'";
         $details=Yii::app()->db->createCommand($sql)->queryAll();
         
         return $details;
      }
      
      protected function afterInsert(& $model)
      {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         $model->rowdeleted='0';      
      }
      
      protected function beforePost(& $model)
      {
          
      }
      
      protected function afterPost(& $model)
      {
        /*
             $idmaker=new idmaker();
             $idmaker->saveRegNum($this->formid, $model->regnum);
         */
      }
      
      protected function onInsertDetail(& $model, $details)
      {
          
      }
      
      protected function onUpdateDetail(& $model, $details)
      {
          
      }
      
        protected function trackActivity($action)
        {
            $this->tracker=new Tracker();
            $this->tracker->init();
            $this->tracker->logActivity($this->formid, $action);
        }
}
