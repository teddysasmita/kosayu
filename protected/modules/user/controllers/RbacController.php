<?php

class RbacController extends Controller {

    public $layout = '//layouts/column2';

    public function filters()
    {
    	return array(
    			'accessControl', // perform access control for CRUD operations
    			'postOnly + delete', // we only allow deletion via POST request
    	);
    }
    
    public function accessRules()
    {
    	return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('createOperation','createRole', 'createTask',
						'deleteOperation','deleteRole', 'deleteTask',
						'updateOperation','updateRole', 'updateTask',
						'viewOperation','viewRole', 'viewTask',
						'adoptOperation','adoptRole', 'adoptTask',
						'deleteAdoptedOperation','deleteAdoptedRole', 'deleteAdoptedTask',
						'index'),
						'users'=>array('admin'),
    			),
    			array('deny',  // deny all users
    				'users'=>array('*'),
    			),
    		);
    	}
    	
    public function actionAdoptoperation($name, $type) {
        $model = new authitemchild;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['authitemchild'])) {
            $model->attributes = $_POST['authitemchild'];
            if ($model->save()) {
                switch ($type) {
                    case 0:
                        $this->redirect(array('viewoperation', 'name' => $name));
                        break;
                    case 1:
                        $this->redirect(array('viewtask', 'name' => $name));
                        break;
                    case 2:
                        $this->redirect(array('viewrole', 'name' => $name));
                        break;
                }
            }
        }

        $this->render('adoptoperation', array(
            'model' => $model, 'name' => $name, 'type'=> $type
        ));
    }

    public function actionAdoptTask($name, $type) {
        $model = new authitemchild;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['authitemchild'])) {
            $model->attributes = $_POST['authitemchild'];
            if ($model->save()) {
                switch ($type) {
                    case 0:
                        $this->redirect(array('viewoperation', 'name' => $name));
                        break;
                    case 1:
                        $this->redirect(array('viewtask', 'name' => $name));
                        break;
                    case 2:
                        $this->redirect(array('viewrole', 'name' => $name));
                        break;
                }
            }
        }

        $this->render('adopttask', array(
            'model' => $model, 'name' => $name, 'type'=> $type
        ));
    }

    public function actionAdoptrole($name, $type) {
        $model = new authitemchild;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['authitemchild'])) {
            $model->attributes = $_POST['authitemchild'];
            if ($model->save())
                if ($model->save()) {
                    switch ($type) {
                        case 0:
                            $this->redirect(array('viewoperation', 'name' => $name));
                            break;
                        case 1:
                            $this->redirect(array('viewtask', 'name' => $name));
                            break;
                        case 2:
                            $this->redirect(array('viewrole', 'name' => $name));
                            break;
                    }
                }
        }

        $this->render('adoptrole', array(
            'model' => $model, 'name' => $name, 'type'=> $type
        ));
    }

    public function actionCreateoperation() {
        $model = new AuthItem;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AuthItem'])) {
            $model->attributes = $_POST['AuthItem'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('createoperation', array(
            'model' => $model,
        ));
    }

    public function actionCreaterole() {
        $model = new AuthItem;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AuthItem'])) {
            $model->attributes = $_POST['AuthItem'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('createrole', array(
            'model' => $model,
        ));
    }

    public function actionCreatetask() {
        $model = new AuthItem;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AuthItem'])) {
            $model->attributes = $_POST['AuthItem'];
            if ($model->save()) {
                $this->createTaskOperations($model->name, $model->description);
                $this->redirect(array('index'));
            }
        }

        $this->render('createtask', array(
            'model' => $model,
        ));
    }

    public function actionDeleteoperation($name) {
        $this->loadModel($name)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function actionDeleterole($name) {
        $this->loadModel($name)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function actionDeletetask($name) {
        $this->loadModel($name)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function actionDeleteadoptedoperation($id) {
        $this->loadModelChild($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionDeleteadoptedtask($id) {
        $this->loadModelChild($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionDeleteadoptedrole($id) {
        $this->loadModelChild($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionUpdateoperation($name) {
        $model = $this->loadModel($name);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AuthItem'])) {
            $model->attributes = $_POST['AuthItem'];
            if ($model->save())
                $this->redirect(array('viewoperation', 'name' => $model->name));
        }

        $this->render('updateoperation', array(
            'model' => $model, 'name' => $model->name
        ));
    }

    public function actionUpdaterole($name) {
        $model = $this->loadModel($name);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AuthItem'])) {
            $model->attributes = $_POST['AuthItem'];
            if ($model->save())
                $this->redirect(array('viewrole', 'name' => $model->name));
        }

        $this->render('updaterole', array(
            'model' => $model, 'name' => $model->name));
    }

    public function actionUpdatetask($name) {
        $model = $this->loadModel($name);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AuthItem'])) {
            $model->attributes = $_POST['AuthItem'];
            if ($model->save())
                $this->redirect(array('viewtask', 'name' => $model->name));
        }

        $this->render('updatetask', array(
            'model' => $model, 'name' => $model->name 
        ));
    }

    public function actionViewoperation($name) {
        $this->render('viewoperation', array(
            'model' => $this->loadModel($name)
        ));
    }

    public function actionViewrole($name) {
        $this->render('viewrole', array(
            'model' => $this->loadModel($name)
        ));
    }

    public function actionViewtask($name) {
        $this->render('viewtask', array(
            'model' => $this->loadModel($name)
        ));
    }

    public function loadModel($id) {
        $model = AuthItem::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelChild($id) {
        $model = authitemchild::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    private function createTaskOperations($name, $description) {
        Yii::app()->authManager->createOperation($name . '-Append', $description.'-Tambah Data');
        Yii::app()->authManager->addItemChild($name, $name . '-Append');
        Yii::app()->authManager->createOperation($name . '-List',$description. '-Lihat Daftar');
        //Yii::app()->authManager->addItemChild($name, $name . '-List');
        Yii::app()->authManager->createOperation($name . '-Delete', $description.'-Hapus Data');
        //Yii::app()->authManager->addItemChild($name, $name . '-Delete');
        Yii::app()->authManager->createOperation($name . '-Update', $description.'-Ubah Data');
        //Yii::app()->authManager->addItemChild($name, $name . '-Update');
    }

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}
