<?php

class ProjectController extends Controller
{
	public $layout='//layouts/column1';

	public function actionIndex()
	{
		$model = new ProjectModel();

		if (isset($_GET['o']))
			Yii::app()->user->setState('project options',$_GET['o']);

		if (isset($_POST['filter']))
		{
			Yii::app()->user->setState('pageCurrent', 1);

			$options = '';
			if (isset($_POST['mine']))
				$options .= 'mine';
			
			Yii::app()->user->setState('project options',$options);

			$nameFilter = $_POST['nameFilter'];
			Yii::app()->user->setState('project nameFilter', $nameFilter);
			
			$sharpTags = ItemModel::sharpTags($_POST['tags']);
			Yii::app()->user->setState('project tags', $sharpTags);
		}
	
		if (isset($_GET['ps']))
		{
			Yii::app()->user->setState('pageSize', $_GET['ps']);
			Yii::app()->user->setState('pageCurrent', 1);
		}
		if (isset($_GET['p']))
			Yii::app()->user->setState('pageCurrent', $_GET['p']); 
		
		$options = Yii::app()->user->getState('project options');
		$pageSize = Yii::app()->user->getState('pageSize') ? Yii::app()->user->getState('pageSize') : 10;
		$pageCurrent = Yii::app()->user->getState('pageCurrent') ? Yii::app()->user->getState('pageCurrent') : 1;
		
		$nameFilter = Yii::app()->user->getState('project nameFilter');		
		$tags = Yii::app()->user->getState('project tags');		
		$count = $model->browseCount($nameFilter, $tags, 0, $options);  
		$pageCount = ceil($count / $pageSize);
		if ($pageCurrent > $pageCount)
		{
			Yii::app()->user->setState('pageCurrent', 1);
			$pageCurrent = 1;
		}

		$projects = $model->browse($nameFilter, $tags, $options, $pageCurrent, $pageSize);  
		
		$this->render('index',array(
			'projects'=>$projects,
			'nameFilter'=> $nameFilter,				
			'tags'=> $tags,				
			'options'=> $options,
			'pageCurrent' => $pageCurrent,
			'pageSize' => $pageSize,
			'pageCount' => $pageCount,
		));
	}

	public function actionNew()
	{
		$model = new ProjectModel();

		if(isset($_POST['save']))
		{
			$model->name= strip_tags($_POST['project_name']);
			$model->description= strip_tags($_POST['description']);
			
			if($model->save())
			{
				$this->redirect($this->createUrl("project/view/" . $model->id));				
			}
		}

		if(isset($_POST['cancel']))
			$this->redirect($this->createUrl('./interaction'));				

		$this->render('new',array(
				'model'=>$model,
		));
	}

	public function actionEdit($id)
	{
		$model = new ProjectModel();
		$model->load($id);
		if ($model->hisLove == 0)
			$this->redirect(Yii::app()->createUrl("./project"));			

		if(isset($_POST['save']))
		{
			$model->name= strip_tags($_POST['project_name']);
			$model->description= strip_tags($_POST['description']);
			if($model->save())
				$this->redirect($this->createUrl('project/view/' . $model->id));				
		} 
		else if(isset($_POST['cancel'])) 
		{
			$this->redirect($this->createUrl('project/view/' . $model->id));				
		}

		$this->render('edit',array(
			'model'=>$model,
		));
	}
	
	public function actionDelete($id)
	{
		$need = new ProjectModel();
		$need->load($id);
		if ($need->hisLove == 0)
			$this->redirect(Yii::app()->createUrl("./project"));			

		$need->delete();
		$this->redirect(Yii::app()->createUrl("./project"));
	}
	
	public function actionView($id)
	{
		$userId = Yii::app()->user->getState('user_id');		
		$project = new ProjectModel();
		$project->load($id);
		$needs = $project->loadNeeds();
		$shares = $project->loadShares();
		if ($project->hisLove == 0)
			$this->redirect(Yii::app()->createUrl("./project"));			

		$this->render('view',array(
			'project'=>$project,
			'userId' => $userId,
			'needs' => $needs,
			'shares' => $shares,
			));
	}	
}

?>