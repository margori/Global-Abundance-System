<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
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
		
		$model = new LoginForm();
		
		if (Yii::app()->user->getState('language') != '')
			$currentLanguage = Yii::app()->user->getState('language');
		else
			$currentLanguage = Yii::app()->language;

		
		$this->render('index', array('model'=>$model, 'currentLanguage'=>$currentLanguage));
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
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm;

		// collect user input data
		if(isset($_POST))
		{
			$model->username=$_POST['username'];
			$model->password=$_POST['password'];
			$model->rememberMe = true;
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->createUrl('./interaction'));
		}
		// display the login form
		$this->render('index',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
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
	
	public function actionBackup()
	{
		if (Yii::app()->user->getState('user_id') != Yii::app()->params['root user id'])
			$this->redirect (Yii::app()->baseUrl);

    $return = '';

		//all of the tables
    $tables =array(
			'item',
			'item_comment',
			'solution',
			'solution_archive',
			'solution_item',
			'unread_comment',
			'user',
			'user_heart',	
			'user_zone',
			);
  
	  //cycle through
		foreach($tables as $table)
		{
			$rows = Yii::app()->db->createCommand()->setText('SELECT * FROM '.$table)->queryAll();

			if (count($rows) > 0)
			{
				foreach($rows as $row)
				{
						$return.= 'INSERT INTO '.$table.' VALUES(';
						$fields = array();					
						foreach ($row as $field) 
						{
							$field = addslashes($field);
							$field = str_replace("\n","\\n",$field);
							if (isset($field)) 
								$fields[] = '\''.$field.'\'' ; 
							else 
								$fields[] .= '\'\''; 

						}
						$fields = implode(', ', $fields);
						$return.= $fields . ");\n";
				}

				$return.= "\n\n";
			}
		}
  
		echo $return;
		exit();
	}
}