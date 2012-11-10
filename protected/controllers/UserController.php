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
			Yii::app()->user->setState('user pageSize', $_GET['ps']);
			Yii::app()->user->setState('pageCurrent', 1);
		}
		if (isset($_GET['p']))
			Yii::app()->user->setState('pageCurrent', $_GET['p']); 

		$pageSize = Yii::app()->user->getState('user pageSize') ? Yii::app()->user->getState('user pageSize') : 25;
		$pageCurrent = Yii::app()->user->getState('pageCurrent') ? Yii::app()->user->getState('pageCurrent') : 1;

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
				
		$messageSave = '';
		$messageChangePassword = '';
		if (isset($_POST['save']))
		{
			$messageSave = $model->validateSave();
			if ($messageSave == '')
				if ($model->save())
					$this->redirect(Yii::app()->createUrl('interaction'));
		}
		if (isset($_POST['saveAbout']))
		{
			if ($model->saveAbout())
				$this->redirect(Yii::app()->createUrl('interaction'));
		}
		else if (isset($_POST['change']))
		{
			$messageChangePassword = $model->validateChangePassword();
			if ($messageChangePassword == '')
				if ($model->changePassword())
					$this->redirect(Yii::app()->createUrl('interaction'));
		}
		else if (isset($_POST['zones']))
		{
			for ($i = 1 ; $i <=4 ; $i++)
			{
				$status = $_POST['zone' . $i . 'Status'];
				$id = $_POST['zone' . $i . 'Id'];
				$top = $_POST['zone' . $i . 'Top'];
				$right = $_POST['zone' . $i . 'Right'];
				$bottom = $_POST['zone' . $i . 'Bottom'];
				$left = $_POST['zone' . $i . 'Left'];
				
				$model->saveZone($status, $id, $top, $right, $bottom, $left);
			}
			$this->redirect(Yii::app()->createUrl('interaction'));
		}
		$this->render('myAccount', array(
				'model'=>$model, 
				'messageSave'=>$messageSave, 
				'messageChangePassword'=>$messageChangePassword, 
				'languages'=>Yii::app()->params['languages'],
				));
	}
	
	public function actionMyAccount()
	{
		$id = Yii::app()->user->getState('user_id');
		$model = new UserForm();
		$model->load($id);
		$zones = $model->loadZones();
		
		$this->render('myAccount', array(
				'model'=>$model, 
				'zones'=>$zones,
				'message'=>'', 
				'languages'=>Yii::app()->params['languages'],
				));
	}

	public function actionView($id)
	{
		$user = new UserForm();
		$user->load($id);
		$user->email = str_replace('@', Yii::t('user', '@'), $user->email);
		$user->email = str_replace('.', Yii::t('user', '.'), $user->email);
		$needs = array();
		$shares = array();
		$zones = array();
		if ($user->hisLove > 0 and $user->myLove > 0)
		{
			$needs = $user->loadNeeds();
			$shares = $user->loadShares();
			$zones = $user->loadZones();
		}
		$this->render('view', array('model'=>$user, 'zones'=>$zones, 'needs'=>$needs, 'shares'=>$shares));
	}
	
	public function actionDelete()
	{
		$id = Yii::app()->user->getState('user_id');
		$model = new UserForm();
		$model->delete($id);
		$this->redirect(Yii::app()->createUrl('site/logout'));		
	}
	
	public function actionLove($id, $returnId)
	{
		$fromUserId = Yii::app()->user->getState('user_id');
		$toUserId = $id;
		$love = $returnId;
		
		$model = new UserForm();
		$model->love($fromUserId, $toUserId, $love);
		
		$this->redirect(Yii::app()->createUrl('user/view/' . $toUserId));		
	}
	
	public function actionBan()
	{
		$this->render('ban');
	}
}