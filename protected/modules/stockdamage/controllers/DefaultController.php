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
	public $formid='AC26';
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
                    
			$model=new Stockdamage;
			$this->afterInsert($model);
                
			Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
			if (!isset(Yii::app()->session['Stockdamage'])) {
				Yii::app()->session['Stockdamage']=$model->attributes;
			} else {
                // use the session to fill the model
				$model->attributes=Yii::app()->session['Stockdamage'];
			}
                
               // Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);
			if (isset($_POST)){
				if(isset($_POST['yt0'])) {
                      //The user pressed the button;
					$model->attributes=$_POST['Stockdamage'];
                    $this->beforePost($model);
                    
					$respond=$this->checkWarehouse($model->idwarehouse) && 
                      	$this->checkSerialNum(Yii::app()->session['Detailstockdamage'], $model);
                      
					if (!$respond) 
						throw new CHttpException(707,'Nomor Serial telah terdaftar.');
					
					$respond=$model->save();
					if (!$respond)
						throw new CHttpException(404,'There is an error in detail posting: '. 
							print_r($model->errors));
						
					if(isset(Yii::app()->session['Detailstockdamage']) ) {
						$details=Yii::app()->session['Detailstockdamage'];
						$respond=$respond&&$this->saveNewDetails($details, $model->idwarehouse);
					} 

					if(!$respond)
						throw new CHttpException(404,'There is an error in detail posting: '. print_r($model->errors));
						
					$this->afterPost($model);
					Yii::app()->session->remove('Stockdamage');
					Yii::app()->session->remove('Detailstockdamage');
					Yii::app()->session->remove('Deletedetailstockdamage');
					$this->redirect(array('view','id'=>$model->id));
                         
				} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
					if($_POST['command']=='adddetail') {
						$model->attributes=$_POST['Stockdamage'];
                        Yii::app()->session['Stockdamage']=$_POST['Stockdamage'];
                        $this->redirect(array('detailstockdamage/create',
                            'id'=>$model->id, 'idwh'=>$model->idwarehouse));
                    } else if ($_POST['command']=='getPO') {
                        $model->attributes=$_POST['Stockdamage'];
                        Yii::app()->session['Stockdamage']=$_POST['Stockdamage'];
                        $this->loadLPB($model->transid, $model->id, $model->idwarehouse);
                    } else if ($_POST['command']=='updatedetail') {
                        $model->attributes=$_POST['Stockdamage'];
                        Yii::app()->session['Stockdamage']=$_POST['Stockdamage'];   
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

			$this->state='update';
            $this->trackActivity('u');

            $model=$this->loadModel($id);
            $this->afterEdit($model);
             
			Yii::app()->session['master']='update';

            if(!isset(Yii::app()->session['Stockdamage']))
                Yii::app()->session['Stockdamage']=$model->attributes;
            else
				$model->attributes=Yii::app()->session['Stockdamage'];

            if(!isset(Yii::app()->session['Detailstockdamage'])) 
               	Yii::app()->session['Detailstockdamage']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);

            if(isset($_POST)) {
				if(isset($_POST['yt0'])) {
					$model->attributes=$_POST['Stockdamage'];
                    $this->beforePost($model);
                    $this->tracker->modify('stockdamage', $id);
                    $respond=$model->save();
                    if(!$respond) 
                    	throw new CHttpException(404,'There is an error in master posting ');
					$this->afterPost($model);

                   	if(isset(Yii::app()->session['Detailstockdamage'])) {
						$details=Yii::app()->session['Detailstockdamage'];
						$respond=$respond&&$this->saveDetails($details);
						if(!$respond) 
							throw new CHttpException(404,'There is an error in detail posting');
                    };
                     
                    if(isset(Yii::app()->session['Deletedetailstockdamage'])) {
                        $deletedetails=Yii::app()->session['Deletedetailstockdamage'];
                        $respond=$respond&&$this->deleteDetails($deletedetails);
                        if(!$respond) 
                           	throw new CHttpException(404,'There is an error in detail deletion');
                    };
                    
					Yii::app()->session->remove('Stockdamage');
					Yii::app()->session->remove('Detailstockdamage');
					Yii::app()->session->remove('Deletedetailstockdamage');
					$this->redirect(array('view','id'=>$model->id));
                 }
			} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
				if($_POST['command']=='adddetail') {
					$model->attributes=$_POST['Stockdamage'];
					Yii::app()->session['Stockdamage']=$_POST['Stockdamage'];
   					$this->redirect(array('detailstockdamage/create',
						'id'=>$model->id, 'idwh'=>$model->idwarehouse));
				} else if ($_POST['command']=='getPO') {
					$model->attributes=$_POST['Stockdamage'];
					Yii::app()->session['Stockdamage']=$_POST['Stockdamage'];
					$this->loadLPB($model->transid, $model->id, $model->idwarehouse);
				} else if ($_POST['command']=='updatedetail') {
					$model->attributes=$_POST['Stockdamage'];
					Yii::app()->session['Stockdamage']=$_POST['Stockdamage'];   
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
            $this->tracker->delete('stockdamage', $id);

            $detailmodels=Detailstockdamage::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailstockdamage', array('iddetail'=>$dm->iddetail));
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

               Yii::app()->session->remove('Stockdamage');
               Yii::app()->session->remove('Detailstockdamage');
               Yii::app()->session->remove('Deletedetailstockdamage');
               $dataProvider=new CActiveDataProvider('Stockdamage',
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
               
                $model=new Stockdamage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Stockdamage']))
			$model->attributes=$_GET['Stockdamage'];

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
                $this->tracker->restore('stockdamage', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Stockdamage');
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
                $id = Yii::app()->tracker->createCommand()->select('id')->from('stockdamage')
                	->where('idtrack = :p_idtrack', array(':p_idtrack'=>$idtrack))
                	->queryScalar();
                $this->tracker->restoreDeleted('detailstockdamage', "id", $id );
                $this->tracker->restoreDeleted('stockdamage', "idtrack", $idtrack);
                
                
                $dataProvider=new CActiveDataProvider('Stockdamage');
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
	 * @return Stockdamage the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Stockdamage::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Stockdamage $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='stockdamage-form')
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
             $model=new Stockdamage;
             $model->attributes=Yii::app()->session['Stockdamage'];

             $details=Yii::app()->session['Detailstockdamage'];
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

             $model=new Stockdamage;
             $model->attributes=Yii::app()->session['Stockdamage'];

             $details=Yii::app()->session['Detailstockdamage'];
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


             $model=new Stockdamage;
             $model->attributes=Yii::app()->session['Stockdamage'];

             $details=Yii::app()->session['Detailstockdamage'];
             $this->afterDeleteDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      

     protected function saveNewDetails(array $details, $idwh)
     {                  
         foreach ($details as $row) {
             $detailmodel=new Detailstockdamage;
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
             $detailmodel=Detailstockdamage::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailstockdamage;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailstockdamage', array('iddetail'=>$detailmodel->iddetail));
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
             $detailmodel=Detailstockdamage::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d', $this->__DETAILFORMID);
                 $this->tracker->delete('detailstockdamage', $detailmodel->iddetail);
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
      $sql="select * from detailstockdamage where id='$id'";
      $details=Yii::app()->db->createCommand($sql)->queryAll();

      return $details;
     }


     protected function afterInsert(& $model)
     {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->idatetime=$idmaker->getDateTime();
         $model->regnum=$idmaker->getRegNum($this->formid);
         //$model->idwarehouse=lookup::WarehouseNameFromIpAddr($_SERVER['REMOTE_ADDR']);
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
     }

     protected function afterPost(& $model)
     {
         $idmaker=new idmaker();
         if ($this->state == 'create') {
         	$idmaker->saveRegNum($this->formid, substr($model->regnum, 2));
         };	   
        
         	
         $details = $this->loadDetails($model->id);
		 foreach($details as $detail) {
			Action::setItemStatusinWarehouse($model->idwarehouse, $detail['serialnum'], '0');
	     };
     }

     protected function beforePost(& $model)
     {
     	
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         if ($this->state == 'create')
         	$model->regnum='DR'.$idmaker->getRegNum($this->formid);
         
         if ($this->state == 'update') {
         	
         	$details = $this->loadDetails($model->id);
         	foreach($details as $detail) {
         		Action::setItemStatusinWarehouse($model->idwarehouse, $detail['serialnum'], '1');
         	};
         }
     }

     protected function beforeDelete(& $model)
     {
     	$details = $this->loadDetails($model->id);
     	foreach($details as $detail) {
     		Action::setItemStatusinWarehouse($model->idwarehouse, $detail['serialnum'], '1');
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
     
      private function loadPO($idpo, $id)
      {
        $details=array();

        $dataPO=Yii::app()->db->createCommand()
           ->select('a.id, b.*')
           ->from('purchasesorders a')
           ->join('detailpurchasesorders b', 'b.id=a.id')
           ->where('a.regnum = :p_id', array(':p_id'=>$idpo) )
           ->queryAll();
        Yii::app()->session->remove('Detailstockdamage');
        $sql=<<<EOS
    	select count(*) as received from stockdamage a 
		join detailstockdamage b on b.id = a.id
		where a.transid = :p_transid and b.iditem = :p_iditem and
        b.serialnum <> 'Belum Diterima'   
EOS;
        $mycommand=Yii::app()->db->createCommand($sql);
         foreach($dataPO as $row) {
         	$mycommand->bindParam(':p_transid', $idpo, PDO::PARAM_STR);
         	$mycommand->bindParam(':p_iditem', $row['iditem'], PDO::PARAM_STR);
         	$accepted=$mycommand->queryScalar();
            for ($index = 0; $index < $row['qty'] - $accepted; $index++) {
               $detail['iddetail']=idmaker::getCurrentID2();
               $detail['id']=$id;
               $detail['iditem']=$row['iditem'];
               $detail['userlog']=Yii::app()->user->id;
               $detail['datetimelog']=idmaker::getDateTime();
               $detail['serialnum']='Belum Diterima';

               $details[]=$detail; 
           	}
        }
        Yii::app()->session['Detailstockdamage']=$details;
      }
     
      private function loadLPB($nolpb, $id, $idwh)
      {
      	$details=array();
      
      	$dataLPB=Yii::app()->db->createCommand()
	      	->select('a.id, b.*')
	      	->from('deliveryorders a')
	      	->join('detaildeliveryorders b', 'b.id=a.id')
	      	->where('a.regnum = :p_regnum and b.idwarehouse = :p_idwarehouse', 
	      		array(':p_regnum'=>$nolpb, ':p_idwarehouse'=> $idwh) )
	      	->queryAll();
      	if ($dataLPB == FALSE ) {
      		$dataLPB=Yii::app()->db->createCommand()
      			->select('a.id, b.*')
      			->from('requestdisplays a')
      			->join('detailrequestdisplays b', 'b.id=a.id')
      			->where('a.regnum = :p_regnum and b.idwarehouse = :p_idwarehouse',
      				array(':p_regnum'=>$nolpb, ':p_idwarehouse'=> $idwh) )
      				->queryAll();
      	}
      	if ($dataLPB == FALSE ) {
      		$dataLPB=Yii::app()->db->createCommand()
      		->select('a.id, b.*')
      		->from('orderretrievals a')
      		->join('detailorderretrievals b', 'b.id=a.id')
      		->where('a.regnum = :p_regnum and b.idwarehouse = :p_idwarehouse',
      				array(':p_regnum'=>$nolpb, ':p_idwarehouse'=> $idwh) )
      				->queryAll();
      	}
      	if ($dataLPB == FALSE ) {
      		$dataLPB=Yii::app()->db->createCommand()
      		->select('a.id, b.*')
      		->from('itemtransfers a')
      		->join('detailitemtransfers b', 'b.id=a.id')
      		->where('a.regnum = :p_regnum and a.idwhsource = :p_idwarehouse',
      				array(':p_regnum'=>$nolpb, ':p_idwarehouse'=> $idwh) )
      				->queryAll();
      	}
      	if ($dataLPB == FALSE ) {
      		$dataLPB=Yii::app()->db->createCommand()
      		->select('a.id, b.*')
      		->from('returstocks a')
      		->join('detailreturstocks b', 'b.id=a.id')
      		->where('a.regnum = :p_regnum and b.idwarehouse = :p_idwarehouse',
      				array(':p_regnum'=>$nolpb, ':p_idwarehouse'=> $idwh) )
      				->queryAll();
      	}
      	if ($dataLPB == FALSE ) {
      		$dataLPB=Yii::app()->db->createCommand()
      		->select('a.id, b.*, c.id as iditem')
      		->from('deliveryordersnt a')
      		->join('detaildeliveryordersnt b', 'b.id=a.id')
      		->join('items c', 'c.name = b.itemname')
      		->where('a.regnum = :p_regnum',
      				array(':p_regnum'=>$nolpb) )
			->queryAll();
      	}
      		
      	
      	$sql=<<<EOS
    	select count(*) as received from stockdamage a
		join detailstockdamage b on b.id = a.id
		where a.transid = :p_transid and b.iditem = :p_iditem and
        b.serialnum <> 'Belum Diterima' and a.idwarehouse = :p_idwarehouse
EOS;
      	$mycommand=Yii::app()->db->createCommand($sql);
      	
      	foreach($dataLPB as $row) {
      		$mycommand->bindParam(':p_transid', $nolpb, PDO::PARAM_STR);
      		$mycommand->bindParam(':p_iditem', $row['iditem'], PDO::PARAM_STR);
      		$mycommand->bindParam(':p_idwarehouse', $idwh);
			$accepted=$mycommand->queryScalar();
			for ($index = 0; $index < $row['qty'] - $accepted; $index++) {
				$detail['iddetail']=idmaker::getCurrentID2();
      			$detail['id']=$id;
				$detail['iditem']=$row['iditem'];
				$detail['userlog']=Yii::app()->user->id;
				$detail['datetimelog']=idmaker::getDateTime();
				$detail['serialnum']='Belum Diterima';
      			$details[]=$detail;
			}
		}
		Yii::app()->session->remove('Detailstockdamage');
		Yii::app()->session['Detailstockdamage']=$details;
	}
      			
      private function checkSerialNum(array $details, $model ) 
      {
         $respond=true;
         
         foreach($details as $detail) {
            if ($detail['serialnum'] !== 'Belum Diterima') {
               /*$count=Yii::app()->db->createCommand()
                  ->select('count(*)')
                  ->from('detailstockdamage')
                  ->where("serialnum = :p_serialnum", array(':serialnum'=>$detail['serialnum']))
                  ->queryScalar();*/
				$count=Yii::app()->db->createCommand()
					->select('count(*)')->from('wh'.$model->idwarehouse)
					->where("serialnum = :p_serialnum and avail = :p_avail",
      					array(':p_serialnum'=>$detail['serialnum'], ':p_avail'=>'0'))
      				->queryScalar();
               $respond=$count==0;
               if(!$respond)
                  break;
            };
         }   
         return $respond;
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
      
      public function actionStockdamagereport()
      {
      	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
      			Yii::app()->user->id))  {
      		$this->trackActivity('v');
      
      		$this->render('report1');
      	} else {
      		throw new CHttpException(404,'You have no authorization for this operation.');
      	};
      }
      
      public function actionGetexcel($startdate, $enddate, $brand, $objects)
      {
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
      			Yii::app()->user->id))  {
      		$this->trackActivity('v');
      			
      		$xl = new PHPExcel();
      		$xl->getProperties()->setCreator("Program GSI Malang")
      		->setLastModifiedBy("Program GSI Malang")
      		->setTitle("Laporan Penjualan")
      		->setSubject("Laporan Penjualan")
      		->setDescription("Laporan Penjualan Bulanan")
      		->setKeywords("Laporan Penjualan")
      		->setCategory("Laporan");
      		$enddate=$enddate.' 23:59:59';
      		$selectfields = <<<EOS
			a.idatetime, a.regnum, a.transid, a.transinfo, a.userlog,
			b.iditem, b.serialnum, c.code
EOS;
      		$selectwhere = <<<EOS
			a.idatetime >= :p_startidatetime and a.idatetime <= :p_endidatetime
EOS;
      		unset($selectparam);
      		$selectparam['p_startidatetime'] = $startdate;
      		$selectparam['p_endidatetime'] = $enddate;
      
      		if (isset($brand) && ($brand <> '')) {
      			$selectwhere .= ' and d.brand = :p_brand';
				$selectparam[':p_brand'] = $brand;
      		}
      		if (isset($objects) && ($objects <> '')) {
      			$selectwhere .= ' and d.objects = :p_objects';
				$selectparam[':p_objects'] = $objects;
      		}
      		$data=Yii::app()->db->createCommand()
      			->select($selectfields)
      			->from('stockdamage a')
      			->join('detailstockdamage b', 'b.id = a.id')
      			->join('warehouses c', 'c.id = a.idwarehouse')
				->join('items d', 'd.id = b.iditem')
      			->where($selectwhere, $selectparam)
      				->order('a.idatetime, a.regnum')
      				->queryAll();
			$headersfield = array( 'idatetime', 'regnum', 'transid', 'transinfo', 'iditem', 'serialnum', 'code', 'userlog', 'suppliername');
			$headersname = array('Tanggal', 'Nomor Urut', 'Jenis Transaksi', 'Info Transaksi', 'Nama Barang', 'Nomor Serial', 
				'Gudang', 'Operator', 'Supplier');
      		for( $i=0;$i<count($headersname); $i++ ) {
      			$xl->setActiveSheetIndex(0)
      				->setCellValueByColumnAndRow($i,1, $headersname[$i]);
      		} 
      							
      		for( $i=0; $i<count($data); $i++){
      			for( $j=0; $j<count($headersfield); $j++ ) {
      				if ($j<count($headersfield)-1)
      					$cellvalue = $data[$i][$headersfield[$j]];
					if ($headersfield[$j] == 'iditem')
      					$cellvalue = lookup::ItemNameFromItemID($data[$i]['iditem']);
					else if ($headersfield[$j] == 'userlog')
      					$cellvalue = lookup::UserNameFromUserID($data[$i]['userlog']);
      				else if ($headersfield[$j] == 'suppliername')
						$cellvalue = lookup::GetSupplierNameFromSerialnum($data[$i]['serialnum']);
					$xl->setActiveSheetindex(0)
      					->setCellValueByColumnAndRow($j,$i+2, $cellvalue);
      			}
			}
      							
      		$xl->getActiveSheet()->setTitle('Laporan Pengeluaran Barang');
      		$xl->setActiveSheetIndex(0);
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment;filename="stockexit-report-'.idmaker::getDateTime().'.xls"');
			header('Cache-Control: max-age=0');
			$xlWriter = PHPExcel_IOFactory::createWriter($xl, 'Excel5');
			$xlWriter->save('php://output');
      	} else {
      		throw new CHttpException(404,'You have no authorization for this operation.');
      	};
	}  
	
	private function autoEntryDisplay($itnum, $idwhsource)
	{
		$datamaster = Yii::app()->db->createCommand()
			->select('a.*')->from("requestdisplays a")
			->where("a.regnum = :p_regnum",
				array(':p_regnum'=>$itnum))
			->queryRow();
		
		$datadetails = Yii::app()->db->createCommand()
			->select('c.iditem, c.serialnum')
			->from("requestdisplays a")
			->join("stockdamage b", "b.transid = a.regnum")
			->join("detailstockdamage c", "c.id = b.id")	
			->where("a.regnum = :p_regnum and b.idwarehouse = :p_idwarehouse",
				array(':p_regnum'=>$itnum, ':p_idwarehouse'=>$idwhsource))
			->queryAll();
		
		Yii::import('application.modules.stockentries.models.*');
		$entrymodel = new Stockentries();
		$entrymodel->id = idmaker::getCurrentID2();
		$entrymodel->regnum = idmaker::getRegNum('AC16');
		$entrymodel->idatetime = idmaker::getDateTime();
		$entrymodel->userlog = Yii::app()->user->id;
		$entrymodel->datetimelog = $entrymodel->idatetime;
		$entrymodel->transid = $itnum;
		$entrymodel->transinfo = 'Permintaan Barang Display - '.$datamaster['regnum']. ' - '.	
			$entrymodel->idatetime;
		$entrymodel->transname = 'AC16';
		$entrymodel->donum = $itnum;
		$entrymodel->idwarehouse = '14103215447754000';
		$entrymodel->validate();
		$respond = $entrymodel->save();
		if ($respond) {
			idmaker::saveRegNum('AC16', $entrymodel->regnum);
		
			foreach($datadetails as $detail) {
				$detailentrymodel = new Detailstockentries();
				$detailentrymodel->id = $entrymodel->id;
				$detailentrymodel->iddetail = idmaker::getCurrentID2();
				$detailentrymodel->userlog = Yii::app()->user->id;
				$detailentrymodel->datetimelog = $entrymodel->idatetime;
				$detailentrymodel->iditem = $detail['iditem'];
				$detailentrymodel->serialnum = $detail['serialnum'];
				
				$detailentrymodel->save();
				Action::entryItemToWarehouse('14103215447754000', $detailentrymodel->iddetail, 
					$detailentrymodel->iditem, $detailentrymodel->serialnum);			
			}
		}
	}
	
	private function removeEntryDisplay($itnum, $warehouse)
	{
		$stockEntry = Yii::app()->db->createCommand()
			->select()->from('stockentries')->where('transid = :p_transid', 
				array(':p_transid'=>$itnum))
			->queryRow();
		
		Yii::app()->db->createCommand()->delete('detailstockentries', 'id = :p_id',
			array(':p_id'=>$stockEntry['id']));
		
		Yii::app()->db->createCommand()->delete('stockentries', 'id = :p_id',
			array(':p_id'=>$stockEntry['id']));
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
					->from("stockdamage a")
					->join("detailstockdamage c", "c.id = a.id")
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
}