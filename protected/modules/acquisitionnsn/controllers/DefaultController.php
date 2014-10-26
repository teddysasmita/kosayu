<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC31';
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
			$this->state='create';
			$this->trackActivity('c');    
                    
			$model=new Acquisitionsnsn;
			$this->afterInsert($model);
                
			Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
			if (!isset(Yii::app()->session['Acquisitionsnsn'])) {
				Yii::app()->session['Acquisitionsnsn']=$model->attributes;
			} else {
                // use the session to fill the model
				$model->attributes=Yii::app()->session['Acquisitionsnsn'];
			}
                
               // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);
				
			if (isset($_POST)){
				if(isset($_POST['yt0'])) {
                      //The user pressed the button;
					$model->attributes=$_POST['Acquisitionsnsn'];
                      
                      
					$this->beforePost($model);
					$respond=$this->checkWarehouse($model->idwarehouse);
					
					$respond=$model->save();
					if(!$respond) 
						throw new CHttpException(404,'There is an error in master posting: '. print_r($model->errors));

						
					$this->afterPost($model);
					Yii::app()->session->remove('Acquisitionsnsn');
					$this->redirect(array('view','id'=>$model->id));
                         
				}
			}

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
             
             Yii::app()->session['master']='update';

             if(!isset(Yii::app()->session['Acquisitionsnsn']))
                Yii::app()->session['Acquisitionsnsn']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Acquisitionsnsn'];

             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
					$model->attributes=$_POST['Acquisitionsnsn'];
					$this->beforePost($model);
					$this->tracker->modify('acquisitionsnsn', $id);
					$respond=$model->save();
					if( !$respond) 
						throw new CHttpException(404,'There is an error in master posting ');
					$this->afterPost($model);
                    
					Yii::app()->session->remove('Acquisitionsnsn');
					$this->redirect(array('view','id'=>$model->id));
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

            $model=$this->loadModel($id);
            $this->trackActivity('d');
            $this->beforeDelete($model);
            $this->tracker->delete('acquisitionsnsn', $id);

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
				
               
               Yii::app()->session->remove('Acquisitionsnsn');
               $dataProvider=new CActiveDataProvider('Acquisitionsnsn',
                  array(
                     'criteria'=>array(
                        'order'=>'idatetime desc'
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
               
                $model=new Acquisitionsnsn('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Acquisitionsnsn']))
			$model->attributes=$_GET['Acquisitionsnsn'];

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
                $this->tracker->restore('acquisitionsnsn', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Acquisitionsnsn');
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
                $id = Yii::app()->tracker->createCommand()->select('id')->from('acquisitionsnsn')
                	->where('idtrack = :p_idtrack', array(':p_idtrack'=>$idtrack))
                	->queryScalar();
                $this->tracker->restoreDeleted('acquisitionsnsn', "idtrack", $idtrack);
                
                
                $dataProvider=new CActiveDataProvider('Acquisitionsnsn');
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
	 * @return Acquisitionsnsn the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Acquisitionsnsn::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Acquisitionsnsn $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='acquisitionsnsn-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
      
     protected function afterInsert(& $model)
     {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->idatetime=$idmaker->getDateTime();
         $model->regnum=$idmaker->getRegNum($this->formid);
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
     }

     protected function afterPost(& $model)
     {
		$idmaker=new idmaker();
        if ($this->state == 'create') {
			$idmaker->saveRegNum($this->formid, substr($model->regnum, 2));
        }
		Yii::import('application.modules.stockentries.models.*');
         	
		$stockentries = new Stockentries();
		$tempid = $model->id;
		$tempid = substr($tempid, 0, 20).'C';
		$stockentries->id = $tempid;
		$stockentries->userlog = $model->userlog;
		$stockentries->datetimelog = idmaker::getDateTime();
		$stockentries->transid = $model->regnum;
		$stockentries->transname = 'AC31';
		$stockentries->transinfo = 'Akuisisi Barang - ' + $model->regnum + ' - ' +
			$model->idatetime;
		$stockentries->idwarehouse = $model->idwarehouse;
		$stockentries->donum = $model->regnum;
		$stockentries->idatetime = $model->idatetime;
		$stockentries->regnum = idmaker::getRegNum('AC3') + 1;
		idmaker::saveRegNum('AC3', $stockentries->regnum);
		if ($stockentries->validate())
			$stockentries->save();
		else
			throw new CHttpException(101,'Error in Stock Entry.');
		for ($i=0; $i < $model->qty; $i++ ) {
			$detailstockentries = new Detailstockentries();
			$detailstockentries->id = $stockentries->id;
			$detailstockentries->iddetail = idmaker::getCurrentID2();
			$detailstockentries->iditem = $model->iditem;
			$detailstockentries->serialnum = 'N.S.T.D.';
			$detailstockentries->userlog = $model->userlog;
			$detailstockentries->datetimelog = idmaker::getDateTime();
			if ($detailstockentries->validate()) {
				$detailstockentries->save();
			} else
				throw new CHttpException(101,'Error in Detail Stock Entry.');
		} 
     }

     protected function beforePost(& $model)
     {
     	
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         if ($this->state == 'create')
         	$model->regnum='DT'.$idmaker->getRegNum($this->formid);
         else
         if ($this->state == 'update') {
         	Yii::import('application.modules.stockentries.models.*');
         	$stockentries = Stockentries::model()->findByPk($tempid);
         	if (! is_null($stockentries))
         		$stockentries->delete();
         	$detailstockentries = Detailstockentries::model()->findAllByAttributes(array('id'=>$tempid));
         	if (count($detailstockentries) > 0)
         	foreach($detailstockentries as $dse) {
         		$dse->delete();
         	};
         	
         }
     }

     protected function beforeDelete(& $model)
     {
     	$tempid = $model->id;
     	$tempid = substr($tempid, 0, 20).'C';
     	Yii::import('application.modules.stockentries.models.*');
     	$stockentries = Stockentries::model()->findByPk($tempid);
     	if (! is_null($stockentries))
     		$stockentries->delete();
     	$detailstockentries = Detailstockentries::model()->findAllByAttributes(array('id'=>$tempid));
     	if (count($detailstockentries) > 0)
     	foreach($detailstockentries as $dse) {
     		$dse->delete();
     	};
     }

     protected function afterDelete(& $model)
     {

     }

     protected function afterEdit(& $model)
     {

     }

     protected function afterInsertDetail(& $model, $details)
     {

     }


     protected function afterUpdateDetail(& $model, $details)
     {

     }

     protected function afterDeleteDetail(& $model, $details)
     {
     }


     protected function trackActivity($action)
     {
         $this->tracker=new Tracker();
         $this->tracker->init();
         $this->tracker->logActivity($this->formid, $action);
     }
     
      
      private function setStatusPO($idpo, array $details)
      {
         $complete=true;
         $partial=false;
         foreach($details as $detail) {
            if($detail['serialnum'] !== 'Belum Diterima')
               $partial=true;
            if($detail['serialnum']=='Belum Diterima') 
               $complete=false;
         }
         if(!$complete && !$partial)
            $status='0';
         if(!$complete && $partial)
            $status='1';
         if($complete && $partial)
            $status='2';
         Action::setStatusPO ($idpo, $status);
      }
      
      private function checkWarehouse($idwarehouse)
      {
         $respond=$idwarehouse<>'NA';
         if (!$respond)
           throw new CHttpException(404,'Gudang belum terdaftar.'); 
         else
            return $respond; 
      }
	
}