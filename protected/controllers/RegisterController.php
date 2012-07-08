<?php

class RegisterController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$model = new RegisterForm();
		
		$this->render('index',array(
				'model'=>$model,
				));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}
	
	public function actionRegister()
	{
		$model= new RegisterForm();

		$message ='';
		// collect user input data
		if(isset($_POST['register']))
		{
			$model->attributes=$_POST;
			
			$message = $model->customValidate();
			if($message == '')
				if ($model->register())
				$this->redirect(Yii::app()->createUrl('interaction'));
		}
		// display the login form
		$model->password = null;
		$model->confirmation = null;
		$this->render('index',array(
				'model'=>$model,
				'message'=>$message,
				));
		
	}
}