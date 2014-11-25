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
	public $formid='AC23';
	public $tracker;
	public $state;
	
	private $salesdata = array();
	private $grosssales = array();

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
                $compositions = array();
                
                $this->state='create';
                $this->trackActivity('c');    
                    
                $model=new Tippayments;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Tippayments'])) {
                   Yii::app()->session['Tippayments']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Tippayments'];
                }
                if (isset($_POST['Tippayments'])) {
                	$model->attributes=$_POST['Tippayments'];
                }
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);
				
				if (isset($_POST)){
					
					if(isset($_POST['yt1'])) {
						$model->attributes=$_POST['Tippayments'];
                      //The user pressed the button;
						$this->beforePost($model);
						
						$respond=$model->save();
						if(!$respond) {
							if (count($model->errors) > 0 )
								$error = implode(',', $model->errors);
							else
								$error = $model->errors;
							throw new CHttpException(5002,'There is an error in master posting: '.$error);
	                    }
	
						if(isset(Yii::app()->session['Detailtippayments']) ) {
							$details=Yii::app()->session['Detailtippayments'];
							$respond=$respond&&$this->saveNewDetails($details);
						} 
						
						if(isset(Yii::app()->session['Detailtippayments2']) ) {
							$details=Yii::app()->session['Detailtippayments2'];
							$respond=$respond&&$this->saveNewDetails2($details);
						}
	
						$this->afterPost($model);
						Yii::app()->session->remove('Tippayments');
						Yii::app()->session->remove('Detailtippayments');
						Yii::app()->session->remove('Detailtippayments2');
						Yii::app()->session->remove('Deletedetailtippayments');
						Yii::app()->session->remove('Deletedetailtippayments2');
						$this->redirect(array('view','id'=>$model->id));

					} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
						if($_POST['command']=='adddetail') {
							$model->attributes=$_POST['Tippayments'];
							Yii::app()->session['Tippayments']=$_POST['Tippayments'];
							$this->redirect(array('detailtippayments/create',
                            	'id'=>$model->id));
                      	} else if ($_POST['command']=='getPO') {
                        	$model->attributes=$_POST['Tippayments'];
                         	Yii::app()->session['Tippayments']=$_POST['Tippayments'];
                         	$this->loadLPB($model->transid, $model->id, $model->idwarehouse);
                      	} else if ($_POST['command']=='updateDetail') {
							$model->attributes=$_POST['Tippayments'];
                         	Yii::app()->session['Tippayments']=$_POST['Tippayments'];
                      	} else if ($_POST['command']=='getComp') {
							$model->attributes=$_POST['Tippayments'];
							$compositions = $this->getCompositions($model->idpartner);
							if ($compositions == 0) 
								$model->idcomp = '-';
							else
								$model->idcomp = '';
                         	Yii::app()->session['Tippayments']=$model->attributes;
                      	} else if ($_POST['command']=='countTip') {
							$model->attributes=$_POST['Tippayments'];
                         	
                         	$this->getSales($model->id, $model->idsticker, $model->ddatetime);
                         	foreach($this->salesdata as $sd) {
                         		$model->totalsales = $model->totalsales + $sd['amount'];
                         		$model->totaldiscount = $model->totaldiscount + $sd['totaldiscount'];
                         	}
                         	Yii::app()->session['Detailtippayments'] = $this->salesdata;
                         	$temp = $this->getSalesDetail($model->id, $model->idpartner, $model->idcomp, 
                         		$model->idsticker, $model->ddatetime);
                         	 Yii::app()->session['Detailtippayments2'] = $temp;
                         	$total = 0;
                         	foreach($temp as $t) {
                         		$total = $total + $t['amount'];
                         	}
                         	$model->amount = idmaker::cashRound($total, 1000);
                         	Yii::app()->session['Tippayments']=$model->attributes;
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

			if(!isset(Yii::app()->session['Tippayments']))
                Yii::app()->session['Tippayments']=$model->attributes;
			else
                $model->attributes=Yii::app()->session['Tippayments'];

			if(!isset(Yii::app()->session['Detailtippayments'])) 
				Yii::app()->session['Detailtippayments']=$this->loadDetails($id);
			if(!isset(Yii::app()->session['Detailtippayments2']))
				Yii::app()->session['Detailtippayments2']=$this->loadDetails2($id);
             
             // Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST)) {
				if(isset($_POST['yt0'])) {
                      //The user pressed the button;
					$model->attributes=$_POST['Tippayments'];
                       
					$this->beforePost($model);
					$respond=$this->checkWarehouse($model->idwarehouse);
					if (!$respond)
						throw new CHttpException(5000,'Lokasi anda tidak terdaftar');
					$respond = $this->checkSerialNum(Yii::app()->session['Detailtippayments'], $model->idwarehouse);
					if (!$respond)
						throw new CHttpException(5001,'Nomor Seri yg anda daftarkan ada yg sdh terdaftar: '. $respond);
	                      
					$respond=$model->save();
					if(!$respond) {
						if (count($model->error) > 0 )
							$error = implode(',', $model->error);
						else
							$error = $model->error;
						throw new CHttpException(5002,'There is an error in master posting: '.$error);
	                }
	
					if(isset(Yii::app()->session['Detailtippayments']) ) {
						$details=Yii::app()->session['Detailtippayments'];
						$respond=$this->saveDetails($details, $model->idwarehouse);
						if (!$respond)
							throw new CHttpException(5002,'There is an error in detail posting');
					} 
	
					$this->afterPost($model);
					Yii::app()->session->remove('Tippayments');
					Yii::app()->session->remove('Detailtippayments');
					Yii::app()->session->remove('Deletedetailtippayments');
					
					$this->redirect(array('view','id'=>$model->id));

				} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
						if($_POST['command']=='adddetail') {
							$model->attributes=$_POST['Tippayments'];
							Yii::app()->session['Tippayments']=$_POST['Tippayments'];
							$this->redirect(array('detailtippayments/create',
                            	'id'=>$model->id));
                      	} else if ($_POST['command']=='getPO') {
                        	$model->attributes=$_POST['Tippayments'];
                         	Yii::app()->session['Tippayments']=$_POST['Tippayments'];
                         	$this->loadLPB($model->transid, $model->id, $model->idwarehouse);
                      	} else if ($_POST['command']=='updateDetail') {
							$model->attributes=$_POST['Tippayments'];
                         	Yii::app()->session['Tippayments']=$_POST['Tippayments'];
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
            $this->tracker->delete('tippayments', $id);

            $detailmodels=Detailtippayments::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailtippayments', array('iddetail'=>$dm->iddetail));
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

               Yii::app()->session->remove('Tippayments');
               Yii::app()->session->remove('Detailtippayments');
               Yii::app()->session->remove('Deletedetailtippayments');
               Yii::app()->session->remove('Detailtippayments2');
               Yii::app()->session->remove('Deletedetailtippayments2');
               $dataProvider=new CActiveDataProvider('Tippayments',
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
               
                $model=new Tippayments('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tippayments']))
			$model->attributes=$_GET['Tippayments'];

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
                $this->tracker->restore('tippayments', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Tippayments');
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
                $id = Yii::app()->tracker->createCommand()->select('id')->from('tippayments')
                	->where('idtrack = :p_idtrack', array(':p_idtrack'=>$idtrack))
                	->queryScalar();
                
                $this->tracker->restoreDeleted('detailtippayments', "id", $id );
                $this->tracker->restoreDeleted('tippayments', "idtrack", $idtrack);
                
                $dataProvider=new CActiveDataProvider('Tippayments');
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
	 * @return Tippayments the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tippayments::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tippayments $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tippayments-form')
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
             $model=new Tippayments;
             $model->attributes=Yii::app()->session['Tippayments'];

             $details=Yii::app()->session['Detailtippayments'];
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

             $model=new Tippayments;
             $model->attributes=Yii::app()->session['Tippayments'];

             $details=Yii::app()->session['Detailtippayments'];
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


             $model=new Tippayments;
             $model->attributes=Yii::app()->session['Tippayments'];

             $details=Yii::app()->session['Detailtippayments'];
             $this->afterDeleteDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
	public function actionShowDetail($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
						
			$model= $this->loadModel($id);
		
			$this->getSales($model->id, $model->idsticker, $model->ddatetime);
	
			$temp = $this->getSalesDetail2($model->id, $model->idpartner, $model->idcomp,
					$model->idsticker, $model->ddatetime);
			
			$this->render('showdetail',array(
				'model'=>$model, 'detail'=>$temp
			));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
	}

     protected function saveNewDetails(array $details)
     {                  
         foreach ($details as $row) {
             $detailmodel=new Detailtippayments;
             $detailmodel->attributes=$row;
             $respond=$detailmodel->insert();
             if (!$respond) {
                break;
             }
         }
         return $respond;
     }
     
     protected function saveNewDetails2(array $details)
     {
     	foreach ($details as $row) {
     		$detailmodel=new Detailtippayments2;
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
             $detailmodel=Detailtippayments::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailtippayments;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailtippayments', array('iddetail'=>$detailmodel->iddetail));
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
     
     protected function saveDetails2(array $details)
     {
     	$idmaker=new idmaker();
     
     	$respond=true;
     	foreach ($details as $row) {
     		$detailmodel=Detailtippayments2::model()->findByPk($row['iddetail']);
     		if($detailmodel==NULL) {
     			$detailmodel=new Detailtippayments2;
     		} else {
     			if(count(array_diff($detailmodel->attributes,$row))) {
     				$this->tracker->init();
     				$this->tracker->modify('detailtippayments', array('iddetail'=>$detailmodel->iddetail));
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
             $detailmodel=Detailtippayments::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d', $this->__DETAILFORMID);
                 $this->tracker->delete('detailtippayments', $detailmodel->iddetail);
                 $respond=$detailmodel->delete();
                 if (!$respond) {
                   break;
                 }
             }
         }
         return $respond;
     }
     
     protected function deleteDetails2(array $details)
     {
     	$respond=true;
     	foreach ($details as $row) {
     		$detailmodel=Detailtippayments2::model()->findByPk($row['iddetail']);
     		if($detailmodel) {
     			$this->tracker->init();
     			$this->trackActivity('d', $this->__DETAILFORMID);
     			$this->tracker->delete('detailtippayments', $detailmodel->iddetail);
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
      $sql="select * from detailtippayments where id='$id'";
      $details=Yii::app()->db->createCommand($sql)->queryAll();

      return $details;
     }
     
     protected function loadDetails2($id)
     {
     	$sql="select * from detailtippayments2 where id='$id'";
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
         $model->idcomp = '-';
         $model->amount = 0;
         $model->totaldiscount = 0;
         $model->totalsales = 0;
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
     }

     protected function afterPost(& $model)
     {
         $idmaker=new idmaker();
         if ($this->state == 'create') {
         	$idmaker->saveRegNum($this->formid, $model->regnum);
         } 
     }

     protected function beforePost(& $model)
     {
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         if ($this->state == 'create')
         	$model->regnum=$idmaker->getRegNum($this->formid);
         else if ($this->state == 'update') {
       
         }
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
     
     
  
      
      private function getCompositions($idpartner)
      {
      	return Yii::app()->db->createCommand()
      		->select('count(*)')
      		->from('detailpartners')
      		->where("id = :p_id", array(':p_id'=>$idpartner))
      		->queryScalar();   	
      }
      
    private function getVoucherNRetur($idsales)
    {
    	$voucheramount = Yii::app()->db->createCommand()
    		->select('sum(amount) as vamount')
    		->from('posreceipts')
    		->where("idpos = :p_idpos and (method = 'V' or method = 'R')",
    			array(':p_idpos'=>$idsales))	
    		->queryScalar();
    	return $voucheramount;
    }
    
    private function getSales($id, $idsticker, $ddatetime)
    {
    	$select1 = <<<EOS
 	a.id as iddetail, a.regnum as invoicenum, (a.total - a.tax) as amount, a.totaldiscount,
    a.idatetime, a.userlog as idcashier, a.datetimelog as cashierlog,
    a.discount, sum(b.qty*b.price) as totalnondisc
EOS;
   		$this->salesdata = Yii::app()->db->createCommand()
   			->select($select1)->from('salespos a')
   			->join('detailsalespos b', 'b.id = a.id')
   			->where("a.idsticker = :p_idsticker and a.idatetime like :p_datetime and b.discount = 0",
   				array(':p_idsticker'=>$idsticker, ':p_datetime'=>$ddatetime.'%'))
   			->group('a.regnum')
   			->order('a.regnum')
   			->queryAll(); 	
   		foreach($this->salesdata as & $sd) {
   			$sd['id'] = $id;	
   			$sd['iddetail'] = idmaker::getCurrentID2();
   			$sd['totaldiscount'] = $sd['totaldiscount'] + $this->getVRDisc($sd['invoicenum'], $sd['id']);
   		};
    }
    
    private function getUnSeenDisc($regnum)
    {
 		$disc = 0;
    	foreach($this->salesdata as $sd) {
    		if ($sd['invoicenum'] == $regnum) {
    			$disc = $sd['discount'] / $sd['totalnondisc'];
    			break;
    		}	
    	}
    	return $disc;
    }
    
    private function getVRDisc($regnum, $id)
    {
    	$disc = 0;
    	foreach($this->salesdata as $sd) {
    		if ($sd['invoicenum'] == $regnum) {
    	// Because voucher or/and retur deduction take place after total
    			$disc = $this->getVoucherNRetur($id) / $sd['amount'];
    			break;
    		}
    	}
    	return $disc;
    }
    
    private function getSalesDetail($id, $idpartner, $idcomp, $idsticker, $ddatetime)
    {
    	if ($idcomp == '-') {
    		$tip = Yii::app()->db->createCommand()->select('a.defaulttip')->from('partners a')
	    		->where("a.id = :p_id ",
	    			array(':p_id'=>$idpartner))
	    		->queryScalar();
    		$tip2 = 1;
    	} else {
    		$tiptemp = Yii::app()->db->createCommand()->select('a.tip, b.defaulttip')->from('detailpartners a')
    			->join('partners b', 'b.id = a.id')
	    		->where("a.id = :p_id and a.iddetail = :p_iddetail",
	    			array(':p_id'=>$idpartner, ':p_iddetail'=>$idcomp))
	    		->queryRow();
    		$tip = $tiptemp['tip'];
    		$tip2 = $tiptemp['tip'] / $tiptemp['defaulttip'];
    	}
    	$sql1 = <<<EOS
    	SELECT a.id, b.iddetail, a.regnum, b.iditem, b.qty, b.price, b.discount, c.pct, c.id as idtipgroup
		FROM detailsalespos b
		JOIN salespos a ON a.id = b.id
		LEFT JOIN (
		detailitemtipgroups d 
		JOIN itemtipgroups c ON c.id = d.id
		) ON d.iditem = b.iditem
    	where a.idsticker = '$idsticker' and a.idatetime like '$ddatetime%' 
    	order by a.regnum
EOS;
		$detailsales = Yii::app()->db->createCommand($sql1)
			->queryAll();
    
    	
    	foreach($detailsales as & $ds) {
    		if ($ds['discount'] == 0) {
    			$ds['discount'] = $this->getUnSeenDisc($ds['regnum']) * $ds['price'];
    		}
    		
    		$ds['discount'] = $this->getVRDisc($ds['regnum'], $ds['id']) * ($ds['price'] - $ds['discount']);
    		if ( is_null($ds['pct']) ) {
    			$ds['pct'] = $tip;
    			$ds['idtipgroup'] = '0';
    		} else
    			$ds['pct'] = $ds['pct'] * $tip2;
    		$ds['amount'] = ($ds['price'] - $ds['discount']) * $ds['qty'] * $ds['pct'] / 100;
    	}
    	
    	$ds2 = array();
    	foreach($detailsales as $ds) {
    		$found = FALSE;
    		if (count($ds2) > 0) {
    			foreach($ds2 as & $d) {
    				if ($d['idtipgroup'] == $ds['idtipgroup'] ) {
    					$d['amount'] = $d['amount'] + $ds['amount'];
    					$found = TRUE;
    					break;
    				} 
    			}
    		};
    		if (! $found) {
    			$temp['id'] = $id;
    			$temp['iddetail'] = idmaker::getCurrentID2();
    			$temp['idtipgroup'] = $ds['idtipgroup'];
    			$temp['amount'] = $ds['amount'];
    			$ds2[] = $temp;
    		};
    	}
    	//return $detailsales;
    	return $ds2;
    }
    
    private function getSalesDetail2($id, $idpartner, $idcomp, $idsticker, $ddatetime)
    {
    	if ($idcomp == '-') {
    		$tip = Yii::app()->db->createCommand()->select('a.defaulttip')->from('partners a')
    		->where("a.id = :p_id ",
    				array(':p_id'=>$idpartner))
    				->queryScalar();
    		$tip2 = 1;
    	} else {
    		$tiptemp = Yii::app()->db->createCommand()->select('a.tip, b.defaulttip')->from('detailpartners a')
    		->join('partners b', 'b.id = a.id')
    		->where("a.id = :p_id and a.iddetail = :p_iddetail",
    				array(':p_id'=>$idpartner, ':p_iddetail'=>$idcomp))
    				->queryRow();
    		$tip = $tiptemp['tip'];
    		$tip2 = $tiptemp['tip'] / $tiptemp['defaulttip'];
    	}
    	$sql1 = <<<EOS
    	SELECT a.id, b.iddetail, a.regnum, b.iditem, b.qty, b.price, b.discount, c.pct, c.id as idtipgroup
		FROM detailsalespos b
		JOIN salespos a ON a.id = b.id
		LEFT JOIN (
		detailitemtipgroups d
		JOIN itemtipgroups c ON c.id = d.id
		) ON d.iditem = b.iditem
    	where a.idsticker = '$idsticker' and a.idatetime like '$ddatetime%'
    	order by a.regnum
EOS;
    	$detailsales = Yii::app()->db->createCommand($sql1)
    	->queryAll();
    
    	foreach($detailsales as & $ds) {
    		if ($ds['discount'] == 0) {
    			$ds['discount'] = $this->getUnSeenDisc($ds['regnum']) * $ds['price'];
    		}
    
    		$ds['discount'] = $this->getVRDisc($ds['regnum'], $ds['id']) * ($ds['price'] - $ds['discount']);
    		if ( is_null($ds['pct']) ) {
    			$ds['pct'] = $tip;
    			$ds['idtipgroup'] = '0';
    		} else
    	 		$ds['pct'] = $ds['pct'] * $tip2;
			$ds['amount'] = ($ds['price'] - $ds['discount']) * $ds['qty'] * $ds['pct'] / 100;
    	}
        
    	return $detailsales;
    }

    public function actionPrint($id)
    {
    	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
    			Yii::app()->user->id)) {
    				$this->trackActivity('p');
    
    		$model=$this->loadModel($id);
    		$detailmodel=$this->loadDetails($id);
    		$detailmodel2=$this->loadDetails2($id);
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			Yii::import('application.modules.tippayment.components.*');
    		require_once('print_tippayment.php');
			ob_clean();
    
    		execute($model, $detailmodel, $detailmodel2);
    	} else {
    		throw new CHttpException(404,'You have no authorization for this operation.');
    	}
    }
      
}