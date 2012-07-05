<?php

class UserController extends Controller
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

	public function actionIndex()
	{
		if (isset($_POST['filter']))
		{
			Yii::app()->user->setState('pageCurrent', 1);
		}

		if (isset($_GET['ps']))
		{
			Yii::app()->user->setState('pageSize', $_GET['ps']);
			Yii::app()->user->setState('pageCurrent', 1);
		}
		if (isset($_GET['p']))
			Yii::app()->user->setState('pageCurrent', $_GET['p']); 

		$pageSize = Yii::app()->user->getState('pageSize') ?: 10;
		$pageCurrent = Yii::app()->user->getState('pageCurrent') ?: 1;

		$userForm = new UserForm();
		$nameFilter = Yii::app()->request->getPost('nameFilter');		

		$userCount = $userForm->browseCount($nameFilter);  
		$pageCount = ceil($userCount / $pageSize);
		if ($pageCurrent > $pageCount)
		{
			Yii::app()->user->setState('pageCurrent', 1);
			$pageCurrent = 1;
		}
		
		$users = $userForm->browse($nameFilter, $pageCurrent, $pageSize);
		
		$this->render('index', array(
				'users'=>$users,
				'nameFilter' => $nameFilter,
				'pageCurrent' => $pageCurrent,
				'pageSize' => $pageSize,
				'pageCount' => $pageCount,
			));
	}
	
	public function actionSave()
	{
		if (isset($_POST['cancel']))
			$this->redirect(Yii::app()->createUrl('interaction'));

		$model = new UserForm();
		$model->attributes = $_POST;
		$model->username = strip_tags($model->username);
		$model->realName = strip_tags($model->realName);
		if ($model->realName == '')
			$model->realName = null;
		$model->email = strip_tags($model->email);
		$model->defaultTags = strip_tags($model->defaultTags);
				
		if (isset($_POST['save']))
		{
			$model->save();
			$this->redirect(Yii::app()->createUrl('interaction'));
		}
		$this->render('index', array('model'=>$model));
	}
	
	public function actionMyAccount()
	{
		$id = Yii::app()->user->getState('user_id');
		$model = new UserForm();
		$model->load($id);
		
		$this->render('myAccount', array('model'=>$model, 'languages'=>Yii::app()->params['languages']));
	}

	public function actionView($id)
	{
		$user = new UserForm();
		$user->load($id);
		$needs = $user->loadNeeds();
		$shares = $user->loadShares();
		$this->render('view', array('model'=>$user, 'needs'=>$needs, 'shares'=>$shares));
	}
	
	public function actionDelete()
	{
		$id = Yii::app()->user->getState('user_id');
		$model = new UserForm();
		$model->delete($id);
		$this->redirect(Yii::app()->createUrl('site/logout'));		
	}
}