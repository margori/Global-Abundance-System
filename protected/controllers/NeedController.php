<?php

class NeedController extends Controller
{
	public $layout='//layouts/column1';

	public function actionIndex()
	{
		$model = new ItemModel();

		if (isset($_GET['o']))
			Yii::app()->user->setState('need options',$_GET['o']);

		if (isset($_POST['filter']))
		{
			Yii::app()->user->setState('pageCurrent', 1);

			$options = '';
			if (isset($_POST['newItem']))
				$options .= 'newItem';
			if (isset($_POST['draftSolutions']))
				$options .= 'draftSolutions';
			if (isset($_POST['mine']))
				$options .= 'mine';
			
			Yii::app()->user->setState('need options',$options);

			$sharpTags = ItemModel::sharpTags($_POST['tags']);
			Yii::app()->user->setState('need tags', $sharpTags);
		}
	
		if (isset($_GET['ps']))
		{
			Yii::app()->user->setState('pageSize', $_GET['ps']);
			Yii::app()->user->setState('pageCurrent', 1);
		}
		if (isset($_GET['p']))
			Yii::app()->user->setState('pageCurrent', $_GET['p']); 
		
		$options = Yii::app()->user->getState('need options');
		$pageSize = Yii::app()->user->getState('pageSize') ? Yii::app()->user->getState('pageSize') : 10;
		$pageCurrent = Yii::app()->user->getState('pageCurrent') ? Yii::app()->user->getState('pageCurrent') : 1;
		
		$tags = Yii::app()->user->getState('need tags');		
		$needCount = $model->browseCount($tags, 0, $options);  
		$pageCount = ceil($needCount / $pageSize);
		if ($pageCurrent > $pageCount)
		{
			Yii::app()->user->setState('pageCurrent', 1);
			$pageCurrent = 1;
		}

		$needs = $model->browse($tags, 0, $options, $pageCurrent, $pageSize);  
		
		$this->render('index',array(
			'items'=>$needs,
			'tags'=> $tags,				
			'options'=> $options,
			'pageCurrent' => $pageCurrent,
			'pageSize' => $pageSize,
			'pageCount' => $pageCount,
		));
	}

	public function actionNew()
	{
		$model = new ItemModel();
		$model->shared = 0;
		$model->quantity = 1;
		$model->description = Yii::app()->user->getState('user_default_tags');
		$sixMonthLater = new DateTime('+6 month');
		$sixMonthLater = $sixMonthLater->format('Y-m-d');
		$model->expiration_date = $sixMonthLater;

		if(isset($_POST['save']))
		{
			$model->attributes=$_POST;
			$model->project_id = $_POST['project'];
			$model->description= strip_tags($model->description);
			
			if($model->save())
			{
				$this->redirect($this->createUrl("need/view/" . $model->id));				
			}
		}

		if(isset($_POST['cancel']))
			$this->redirect($this->createUrl('./interaction'));				

		$projects = ProjectModel::loadMyProject();
		$project_id = intval($_GET['project_id']);
		
		$this->render('new',array(
			'model'=>$model,
			'projects' => $projects,
			'project_id' => $project_id,
		));
	}

	public function actionEdit($id)
	{
		$model = new ItemModel();
		$model->load($id);
		if ($model->hisLove == 0)
			$this->redirect(Yii::app()->createUrl("./need"));			

		if(isset($_POST['save']))
		{
			$model->attributes=$_POST;
			$model->project_id = $_POST['project'];
			$model->description= strip_tags($model->description);
			if($model->save())
				$this->redirect($this->createUrl('need/view/' . $model->id));				
		} 
		else if(isset($_POST['cancel'])) 
		{
			$this->redirect($this->createUrl('need/view/' . $model->id));				
		}

		if(isset($_POST['cancel']))
			$this->redirect($this->createUrl('./interaction'));				

		$projects = ProjectModel::loadMyProject();

		$this->render('edit',array(
			'model'=>$model,
			'projects' => $projects,
		));
	}
	
	public function actionDelete($id)
	{
		$need = new ItemModel();
		$need->load($id);
		if ($need->hisLove == 0)
			$this->redirect(Yii::app()->createUrl("./need"));			

		$need->delete();
		$this->redirect(Yii::app()->createUrl("./need"));
	}
	
	public function actionView($id)
	{
		$userId = Yii::app()->user->getState('user_id');		
		$need = new ItemModel();
		$need->load($id);
		if ($need->hisLove == 0)
			$this->redirect(Yii::app()->createUrl("./need"));			
		$solutions = $need->loadSolutions();
		$comments = $need->loadComments();

		$this->render('view',array(
			'need'=>$need,
			'solutions' => $solutions,
			'comments' => $comments,
			'userId' => $userId,
			));
	}
	
	public function actionNewSolution($id)
	{
		$itemForm = new ItemModel();
		$itemForm->newSolution($id);
		$this->redirect(Yii::app()->createUrl("need/view/$id"));
	}

	public function actionCompleteSolution($id)
	{
		$need = new ItemModel();
		$need->load($id);
		
		$share = new ItemModel();
		$share->description = $need->description;
		$share->shared = 1;
		$share->quantity = 1;
		$sixMonthLater = new DateTime('+6 month');
		$sixMonthLater = $sixMonthLater->format('Y-m-d');
		$share->expiration_date = $sixMonthLater;
		
		$share->save();
		
	  $solutionId = $need->newSolution($need->id);
		
		$command = Yii::app()->db->createCommand();	
		$command->insert('solution_item', 
			array(
				'solution_id' => $solutionId,
				'item_id' => $share->id,
				));
		
		$this->redirect(Yii::app()->createUrl("need/view/$id"));		
	}

	public function actionDeleteSolution($id, $returnId)
	{
		$itemForm = new ItemModel();
		$itemForm->deleteSolution($id);
		$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
	}

	public function actionSolution($id)
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
		$solution->load($solutionId);
		$solution->loadItems();

		$needId = $solution->item_id;
		
		$need = new ItemModel();
		$need->load($needId);

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
		
		$excludeId = null;
		foreach ($solution->items as $item)
			$excludeId[] = $item['item_id'];
		if (isset($excludeId))
			$excludeId = implode(', ', $excludeId);
				
		$shares = $model->browse($sharpTags, 1, $options, $pageCurrent, $pageSize, $needUserId, $excludeId);

		$userId = Yii::app()->user->getState('user_id');	

		$this->render('solution',array(
			'need' => $need,
			'solution' => $solution,
			'shares'=> $shares,
			'tags' => $sharpTags,
			'options'=> $options,
			'pageCurrent' => $pageCurrent,
			'pageSize' => $pageSize,
			'pageCount' => $pageCount,
			'userId' => $userId,
			));		
	}
	
	public function actionAddItem($id, $returnId)
	{
		if (Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->createUrl("need/view/$returnId"));

		$solution =new SolutionModel();
		$solution->id = $id;
		
		$itemId = $returnId;
		$solution->addItem($itemId);
		
		$this->redirect(Yii::app()->createUrl('need/solution/' .$solution->id));		
	}

	public function actionDeleteItemSolutionBackToNeed($id, $returnId)
	{
		if (Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
		$solution = new SolutionModel();
		$solution->load($id);
		$solution->deleteItem($returnId);
		$this->redirect(Yii::app()->createUrl('need/view/' . $solution->item_id));
	}

	public function actionDeleteSolutionItemBackToSolution($id, $returnId)
	{
		if (Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->createUrl("need/solution/$returnId"));
		$solution = new SolutionModel();
		$solution->load($id);
		$solution->deleteItem($returnId);
		$this->redirect(Yii::app()->createUrl('need/solution/' . $solution->id));
	}
	
	public function actionDraft($id, $returnId)
	{
		$itemForm = new ItemModel();
		$itemForm->markAsDraft($id);
		$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
	}

	public function actionComplete($id, $returnId)
	{
		$itemForm = new ItemModel();
		$itemForm->markAsComplete($id);
		$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
	}

	public function actionTake($id, $returnId)
	{
		$itemForm = new ItemModel();
		$itemForm->take($id);
		$this->redirect(Yii::app()->createUrl("./need"));
	}

	public function actionComment($id)
	{
		if (isset($_POST['comment_button']))
		{
			$itemForm = new ItemModel();
			$comment = $_POST['comment'];
			$comment=  strip_tags($comment);
			$userId = Yii::app()->user->getState('user_id');
			$itemForm->comment($id,$userId, $comment);
		}
		$this->redirect(Yii::app()->createUrl("need/view/$id"));
	}

	public function actionDeleteComment($id, $returnId)
	{
		$itemForm = new ItemModel();
		$itemForm->deleteComment($id);
		$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
	}
}
