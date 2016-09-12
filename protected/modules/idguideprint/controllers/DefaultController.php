<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC68';
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
                    
                $model=new Idguideprints;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Idguideprints'])) {
                   Yii::app()->session['Idguideprints']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Idguideprints'];
                }
                
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);

                if (isset($_POST)){
                	if(isset($_POST['yt0'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Idguideprints'];
                      
                      
                      $this->beforePost($model);
                      $respond=true;
                      // && $this->checkSerialNum(Yii::app()->session['Detailidguideprints']);
                      if ($respond) {
                         $respond=$model->save();
                         if(!$respond) {
                             throw new CHttpException(404,'There is an error in master posting'. ' '. $model->errors);
                         }

                         if(isset(Yii::app()->session['Detailidguideprints']) ) {
                           $details=Yii::app()->session['Detailidguideprints'];
                           $respond=$respond&&$this->saveNewDetails($details);
                         } 

                         if($respond) {
                            $this->afterPost($model);
                            Yii::app()->session->remove('Idguideprints');
                            Yii::app()->session->remove('Detailidguideprints');
                            $this->redirect(array('view','id'=>$model->id));
                         } 
                         
                      } else {
                        throw new CHttpException(404,'Nomor Serial telah terdaftar.');
                     }     
                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Idguideprints'];
                         Yii::app()->session['Idguideprints']=$_POST['Idguideprints'];
                         $this->redirect(array('detailidguideprints/create',
                            'id'=>$model->id));
                      } else if ($_POST['command']=='getPO') {
                         $model->attributes=$_POST['Idguideprints'];
                         Yii::app()->session['Idguideprints']=$_POST['Idguideprints'];
                         $this->loadPO($model->transid, $model->id);
                      } else if ($_POST['command'] == 'batchcode') {
                      	 $model->attributes = $_POST['Idguideprints'];
                      	 Yii::app()->session['Idguideprints']=$model->attributes;
                      	 $newidguides = $this->prepareIdguide($_POST['batchcode'], $_POST['batchrep'],
                      	 	$model->id);
                      	
                      	 if (isset(Yii::app()->session['Detailidguideprints'])) {
						 	$idguides = Yii::app()->session['Detailidguideprints'];
						 	$idguides = array_merge($idguides, $newidguides);
                      	 } else 
                      	 	$idguides = $newidguides;
                      	 Yii::app()->session['Detailidguideprints'] = $idguides;
                   	  }
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

             $this->state='u';
             $this->trackActivity('u');

             $model=$this->loadModel($id);
             $this->afterEdit($model);
             
             Yii::app()->session['master']='update';

             if(!isset(Yii::app()->session['Idguideprints']))
                Yii::app()->session['Idguideprints']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Idguideprints'];

             if(!isset(Yii::app()->session['Detailidguideprints'])) 
               Yii::app()->session['Detailidguideprints']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Idguideprints'];
                     $this->beforePost($model);
                     $this->tracker->modify('idguideprints', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                     	throw new CHttpException(404,'There is an error in master posting ');
                     }

                     if(isset(Yii::app()->session['Detailidguideprints'])) {
                         $details=Yii::app()->session['Detailidguideprints'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetailidguideprints'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailidguideprints'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                    
                     if($respond) {
                         Yii::app()->session->remove('Idguideprints');
                         Yii::app()->session->remove('Detailidguideprints');
                         Yii::app()->session->remove('DeleteDetailidguideprints');
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

            $model=$this->loadModel($id);
            $this->trackActivity('d');
            $this->beforeDelete($model);
            $this->tracker->delete('idguideprints', $id);

            $detailmodels=Detailidguideprints::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailidguideprints', array('iddetail'=>$dm->iddetail));
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

               Yii::app()->session->remove('Idguideprints');
               Yii::app()->session->remove('Detailidguideprints');
               Yii::app()->session->remove('DeleteDetailidguideprints');
               $dataProvider=new CActiveDataProvider('Idguideprints',
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
               
                $model=new Idguideprints('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Idguideprints']))
			$model->attributes=$_GET['Idguideprints'];

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
                $this->tracker->restore('idguideprints', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Idguideprints');
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
                $this->tracker->restoreDeleted('idguideprints', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Idguideprints');
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
	 * @return Idguideprints the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Idguideprints::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Idguideprints $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='idguideprints-form')
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
             $model=new Idguideprints;
             $model->attributes=Yii::app()->session['Idguideprints'];

             $details=Yii::app()->session['Detailidguideprints'];
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

             $model=new Idguideprints;
             $model->attributes=Yii::app()->session['Idguideprints'];

             $details=Yii::app()->session['Detailidguideprints'];
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


             $model=new Idguideprints;
             $model->attributes=Yii::app()->session['Idguideprints'];

             $details=Yii::app()->session['Detailidguideprints'];
             $this->afterDeleteDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
     public function actionPrintCards($id)
     {
     	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
     			Yii::app()->user->id))  {
     		$model = $this->loadModel($id);
     		$details = $this->loadDetails($id);
     		$colnum = floor(($model->paperwidth - (2* $model->papersidem)) / ($model->labelwidth + (2 *$model->labelsidem)));
     		$rownum = floor(($model->paperheight - (2* $model->paperbotm)) / ($model->labelheight + (2 * $model->labelbotm)));
     		$pagenum = ceil(count($details) / ($colnum * $rownum));
   	     	$paperinfo = ['colnum' => $colnum, 'rownum' => $rownum, 'pagenum' => $pagenum];
   	     	$this->renderPartial('printcard',
     					['model'=>$model, 'details'=>$details, 'paperinfo'=> $paperinfo]
     		);
     	} else {
     		throw new CHttpException(404,'You have no authorization for this operation.');
     	}
     }
     
     public function actionPrintBacks($id)
     {
     	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
     			Yii::app()->user->id))  {
     				$model = $this->loadModel($id);
     				$details = $this->loadDetails($id);
     				$colnum = floor(($model->paperwidth - (2* $model->papersidem)) / ($model->labelwidth + (2 *$model->labelsidem)));
     				$rownum = floor(($model->paperheight - (2* $model->paperbotm)) / ($model->labelheight + (2 * $model->labelbotm)));
     				$pagenum = ceil(count($details) / ($colnum * $rownum));
     				$paperinfo = ['colnum' => $colnum, 'rownum' => $rownum, 'pagenum' => $pagenum];
     				$this->renderPartial('printback',
     						['model'=>$model, 'details'=>$details, 'paperinfo'=> $paperinfo]
     				);
     			} else {
     				throw new CHttpException(404,'You have no authorization for this operation.');
     			}
     }

     protected function saveNewDetails(array $details)
     {                  
         foreach ($details as $row) {
             $detailmodel=new Detailidguideprints;
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

         $respond=true;
         foreach ($details as $row) {
             $detailmodel=Detailidguideprints::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailidguideprints;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailidguideprints', array('iddetail'=>$detailmodel->iddetail));
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
             $detailmodel=Detailidguideprints::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d', $this->__DETAILFORMID);
                 $this->tracker->delete('detailidguideprints', $detailmodel->id);
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
      $sql="select * from detailidguideprints where id='$id'";
      $details=Yii::app()->db->createCommand($sql)->queryAll();

      return $details;
     }


     protected function afterInsert(& $model)
     {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->idatetime=$idmaker->getDateTime();
         $model->regnum=$idmaker->getRegNum($this->formid);
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         $model->papersize = 'A4';
         $model->paperwidth = 29.7;
         $model->paperheight = 21;
         $model->papersidem = 5;
         $model->paperbotm = 5;
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
     
      private function checkSerialNum(array $details ) 
      {
         $respond=true;
         
         foreach($details as $detail) {
            if ($detail['serialnum'] !== 'Belum Diterima') {
               $count=Yii::app()->db->createCommand()
                  ->select('count(*)')
                  ->from('detailidguideprints')
                  ->where("serialnum = :serialnum", array(':serialnum'=>$detail['serialnum']))
                  ->queryScalar();
               $respond=$count==0;
               if(!$respond)
                  break;
            };
         }   
         return $respond;
      }
     
	public function actionPrintIdguide($id) 
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
			$master = $this->loadModel($id);
			$detail = $this->loadDetails($id);
			
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			$mypdf=new Idguideprintpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$mypdf->init();
			$mypdf->LoadData($master->idguidetype, $master->labelwidth, $master->labelheight,  
					$detail);
			$mypdf->display();
			$mypdf->output('Cetak Idguide'.'-'.date('Ymd').'.pdf', 'I');
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}	
	}
	
	private function prepareIdguide($code, $rep, $id) 
	{
		for ($i = 0; $i < $rep; $i++) {
			$temp['iddetail'] = idmaker::getCurrentID2();
			$temp['id'] = $id;
			$temp['num'] = $code;
			$temp['userlog'] = Yii::app()->user->id;
			$temp['datetimelog'] = idmaker::getDateTime();

			$newdata[] = $temp;				
		}
		
		return $newdata;
	}
}
