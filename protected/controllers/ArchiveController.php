<?php

class ArchiveController extends Controller
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
			Yii::app()->user->setState('archive tags', $_POST['tags']);
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

		$archiveForm = new ArchiveForm();
		$tags = Yii::app()->user->getState('archive tags');		
		$tags = ItemForm::sharpTags($tags);

		$archiveCount = $archiveForm->browseCount($tags);  
		$pageCount = ceil($archiveCount / $pageSize);
		if ($pageCurrent > $pageCount)
		{
			Yii::app()->user->setState('pageCurrent', 1);
			$pageCurrent = 1;
		}
		
		$archives = $archiveForm->browse($tags, $pageCurrent, $pageSize);
		
		$this->render('index', array(
				'archives' => $archives,
				'tags' => $tags,
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
		$this->render('myAccount', array('model'=>$model));
	}

	public function actionView($id)
	{
		$model = new UserForm();
		$model->load($id);
		$this->render('view', array('model'=>$model));
	}
	
	public function actionDelete()
	{
		$id = Yii::app()->user->getState('user_id');
		$model = new UserForm();
		$model->delete($id);
		$this->redirect(Yii::app()->createUrl('site/logout'));		
	}
}