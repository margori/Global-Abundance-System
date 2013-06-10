<?php

class SolutionController extends Controller
{
	public function actions()
	{
		return array(
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

		$pageSize = Yii::app()->user->getState('pageSize') ? Yii::app()->user->getState('pageSize') : 10;
		$pageCurrent = Yii::app()->user->getState('pageCurrent') ? Yii::app()->user->getState('pageCurrent') : 1;

		$archiveModel = new ArchiveModel();
		$tags = Yii::app()->user->getState('archive tags');		
		$tags = ItemModel::sharpTags($tags);

		$archiveCount = $archiveModel->browseCount($tags);  
		$pageCount = ceil($archiveCount / $pageSize);
		if ($pageCurrent > $pageCount)
		{
			Yii::app()->user->setState('pageCurrent', 1);
			$pageCurrent = 1;
		}
		
		$archives = $archiveModel->browse($tags, $pageCurrent, $pageSize);
		
		$this->render('index', array(
				
				'solution' => $solution,
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

		$model = new UserModel();
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
		$model = new UserModel();
		$model->load($id);
		$this->render('myAccount', array('model'=>$model));
	}

	public function actionView($id)
	{
		$model = new UserModel();
		$model->load($id);
		$this->render('view', array('model'=>$model));
	}
	
	public function actionDelete()
	{
		$id = Yii::app()->user->getState('user_id');
		$model = new UserModel();
		$model->delete($id);
		$this->redirect(Yii::app()->createUrl('site/logout'));		
	}

	public function actionSolution($id, $returnId)
	{
		if (isset($_GET['o']))
			Yii::app()->user->setState('add item options',$_GET['o']);
		
		if (isset($_POST['filter']))
		{
			Yii::app()->user->setState('pageCurrent', 1);

			$options = '';
			if (isset($_POST['mine']))
				$options .= 'mine';
			
			Yii::app()->user->setState('add item options',$options);
		}

		if (isset($_GET['ps']))
		{
			Yii::app()->user->setState('pageSize', $_GET['ps']);
			Yii::app()->user->setState('pageCurrent', 1);
		}
		if (isset($_GET['p']))
			Yii::app()->user->setState('pageCurrent', $_GET['p']); 

		$options = Yii::app()->user->getState('share options') ? Yii::app()->user->getState('share options') : '';
		$pageSize = Yii::app()->user->getState('pageSize') ? Yii::app()->user->getState('pageSize') : 10;
		$pageCurrent = Yii::app()->user->getState('pageCurrent') ? Yii::app()->user->getState('pageCurrent') : 1;

		$solutionId = $id;
		$solution = new SolutionModel();
		$solution->load($id);
		$solution->loadItems();
		$needId = $returnId;
		
		if (isset($_POST['cancel']))
		{
			Yii::app()->user->setState('addItems', null);
			$this->redirect(Yii::app()->createUrl("need/view/$needId"));
		}

		$needUserId = ItemModel::GetUserId($needId); 

		$model = new ItemModel();
		$tags = Yii::app()->request->getPost('tags');		
		$sharpTags = $model->sharpTags($tags);
		$shareCount = $model->browseCount($sharpTags, 0, $options, $needUserId);  
		$pageCount = ceil($shareCount / $pageSize);
		if ($pageCurrent > $pageCount)
		{
			Yii::app()->user->setState('pageCurrent', 1);
			$pageCurrent = 1;
		}
				

		$shares = $model->browse($sharpTags, 1, $options, $pageCurrent, $pageSize, $needUserId);

		$this->render('solution',array(
			'shares'=>$shares,
			'tags' => $sharpTags,
			'solution' => $solution,
			'needId' => $needId,
			'options'=> $options,
			'pageCurrent' => $pageCurrent,
			'pageSize' => $pageSize,
			'pageCount' => $pageCount,
			));		
	}
}

