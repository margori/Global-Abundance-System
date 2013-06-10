<?php

class SiteController extends Controller
{
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
		$model = new LoginModel();
		
		if (Yii::app()->user->getState('language') != '')
			$currentLanguage = Yii::app()->user->getState('language');
		else
			$currentLanguage = Yii::app()->language;
		
		$this->render('index', array('model'=>$model, 'currentLanguage'=>$currentLanguage));
	}
	
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

	public function actionLogin()
	{
		$model = new LoginModel;

		if(isset($_POST))
		{
			$model->username=$_POST['username'];
			$model->password=$_POST['password'];
			$model->rememberMe = true;
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->createUrl('./interaction'));
		}

		$this->render('index',array('model'=>$model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
        
  public function actionLanguage($data)
  {		
		foreach (Yii::app()->params['languages'] as $iso => $language)
			if ($iso == $data)
				Yii::app()->user->setState('language', $iso);            
		
		$previousUrl = Yii::app()->user->getState('previous url');
    $this->redirect($previousUrl);
  }
	
	public function actionSuggestions()
	{
		$this->render('suggestions');
	}

	public function actionLove()
	{
		$this->render('love');
	}
}

?>
