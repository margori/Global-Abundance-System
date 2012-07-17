<?php

class NeedController extends Controller
{
	public $layout='//layouts/column1';

	public function actionIndex()
	{
		$model = new ItemForm();

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

			$sharpTags = ItemForm::sharpTags($_POST['tags']);
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
		$pageSize = Yii::app()->user->getState('pageSize') ?: 10;
		$pageCurrent = Yii::app()->user->getState('pageCurrent') ?: 1;
		
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
		$model = new ItemForm();
		$model->shared = 0;
		$model->quantity = 1;
		$model->description = Yii::app()->user->getState('user_default_tags');
		$today = new DateTime();
		$model->expiration_date = $today->add(new DateInterval('P6M'))->format('Y-m-d'); // Today plus 6 month

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['save']))
		{
			$model->attributes=$_POST['ItemForm'];
			$model->creation_date = $today->format('Y-m-d');
			$model->description=  strip_tags($model->description);
			
			if($model->save())
			{
				$this->redirect($this->createUrl("need/view/" . $model->id));				
			}
		}

		if(isset($_POST['cancel']))
			$this->redirect($this->createUrl('./interaction'));				

		$this->render('new',array(
				'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id)
	{
		$model= $this->loadNeed($id);
		if ($need->hisLove == 0)
			$this->redirect(Yii::app()->createUrl("./need"));			

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['save']))
		{
			$model->attributes=$_POST['ItemForm'];
			$model->description=  strip_tags($model->description);
			if($model->save())
				$this->redirect($this->createUrl('need/view/' . $model->id));				
		} 
		else if(isset($_POST['cancel'])) 
		{
			$this->redirect($this->createUrl('need/view/' . $model->id));				
		}

		$this->render('edit',array(
			'model'=>$model,
		));
	}
	
	public function actionDelete($id)
	{
		$need = $this->loadNeed($id);
		if ($need->hisLove == 0)
			$this->redirect(Yii::app()->createUrl("./need"));			

		$itemForm = new ItemForm();
		$itemForm->id = $id;
		$itemForm->delete();
		$this->redirect(Yii::app()->createUrl("./need"));
	}
	
	// ------------------

	public function actionView($id)
	{
		$userId = Yii::app()->user->getState('user_id');
		$need = $this->loadNeed($id);
		if ($need->hisLove == 0)
			$this->redirect(Yii::app()->createUrl("./need"));			
		$solutions = $this->loadSolutions($id);
		$comments = $this->loadComments($id);

		$this->render('view',array(
			'need'=>$need,
			'solutions' => $solutions,
			'comments' => $comments,
			'userId' => $userId,
			));
	}
	
	public function actionNewSolution($id)
	{
		$itemForm = new ItemForm();
		$itemForm->newSolution($id);
		$this->redirect(Yii::app()->createUrl("need/view/$id"));
	}

	public function actionDeleteSolution($id, $returnId)
	{
		$itemForm = new ItemForm();
		$itemForm->deleteSolution($id);
		$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
	}

	public function actionAddItem($id, $returnId)
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

		$options = Yii::app()->user->getState('share options') ?: '';
		$pageSize = Yii::app()->user->getState('pageSize') ?: 10;
		$pageCurrent = Yii::app()->user->getState('pageCurrent') ?: 1;

		$solutionId = $id;
		$needId = $returnId;
		
		if (isset($_POST['addReturn']))
		{
			$this->addSessionItems();
			$this->addItems($solutionId);
			Yii::app()->user->setState('addItems', null);
			$this->redirect(Yii::app()->createUrl("need/view/$needId"));
		}
		if (isset($_POST['addContinue']))
		{
			$this->addSessionItems();
			$this->addItems($solutionId);
			Yii::app()->user->setState('addItems', null);
		}
		else if (isset($_POST['cancel']))
		{
			Yii::app()->user->setState('addItems', null);
			$this->redirect(Yii::app()->createUrl("need/view/$needId"));
		}

		$needUserId = ItemForm::GetUserId($needId); 

		$model = new ItemForm();
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

		$this->render('addItem',array(
			'shares'=>$shares,
			'tags' => $sharpTags,
			'solutionId' => $solutionId,
			'needId' => $needId,
			'options'=> $options,
			'pageCurrent' => $pageCurrent,
			'pageSize' => $pageSize,
			'pageCount' => $pageCount,
			));		
	}

	public function actionDeleteSolutionItem($id, $returnId)
	{
		if (Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
		$itemForm = new ItemForm();
		$itemForm->deleteSolutionItem($id);
		$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
	}
	
	public function actionDraft($id, $returnId)
	{
		$itemForm = new ItemForm();
		$itemForm->markAsDraft($id);
		$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
	}

	public function actionComplete($id, $returnId)
	{
		$itemForm = new ItemForm();
		$itemForm->markAsComplete($id);
		$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
	}

	public function actionTake($id, $returnId)
	{
		$itemForm = new ItemForm();
		$itemForm->take($id);
		$this->redirect(Yii::app()->createUrl("./need"));
	}

	public function actionComment($id)
	{
		if (isset($_POST['comment_button']))
		{
			$itemForm = new ItemForm();
			$comment = $_POST['comment'];
			$comment=  strip_tags($comment);
			$userId = Yii::app()->user->getState('user_id');
			$itemForm->comment($id,$userId, $comment);
		}
		$this->redirect(Yii::app()->createUrl("need/view/$id"));
	}

	public function actionDeleteComment($id, $returnId)
	{
		$itemForm = new ItemForm();
		$itemForm->deleteComment($id);
		$this->redirect(Yii::app()->createUrl("need/view/$returnId"));
	}

	// ------------------
	public function loadNeed($id)
	{
		$command = Yii::app()->db->createCommand();
		$userId = Yii::app()->user->getState('user_id');
	
		$sql = 'select i.*, coalesce(u.real_name, u.username) as username ';
		if ($userId > 0)
			$sql .= ', coalesce(uh.love, 1) as hisLove ';
		else	
			$sql .= ', 1 as hisLove ';
		
		$sql .= 'from item i 
				inner join `user` u
					on u.id = i.user_id ';
		
		if ($userId > 0)
			$sql .= "left join user_heart uh
					on uh.from_user_id = i.user_id and uh.to_user_id = $userId ";
		
		$sql .= "where i.id = $id ";
		
		$row = $command->setText($sql)->queryRow();
		
		if($row === null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		$model = new ItemForm();
		$model->attributes = $row;
		return $model;
	}

	public function loadSolutions($itemId)
	{
		$command = Yii::app()->db->createCommand();
		
		$solutions = $command->setText("select s.*, coalesce(u.real_name, u.username) as user_name
				from solution s inner join user u on u.id = s.user_id
				where s.item_id = $itemId")->queryAll();
		
		for($i = 0; $i < count($solutions); $i++)
		{
			$solutionId = $solutions[$i]['id'];
			$command = Yii::app()->db->createCommand();
			$solutionItems = $command->select('si.id, si.solution_id, si.item_id, i.description')
							->from('(solution_item si
													inner join item i
													on i.id = si.item_id)')
							->where('si.solution_id = ' . $solutionId)
							->queryAll();
			$solutions[$i]['items'] = $solutionItems;
			
			$command = Yii::app()->db->createCommand();
			$missingEmails = $command->setText("select coalesce(u.real_name, u.username) as user_name
				from
					item i
					inner join user u on u.id = i.user_id
				where 
					i.id = $itemId
					and (u.email is null or u.email = '')

				union

				select coalesce(u.real_name, u.username) as user_name
				from solution_item si
					inner join item i on i.id = si.item_id
					inner join user u on u.id = i.user_id
				where si.solution_id = $solutionId						
					and (u.email is null or u.email = '')
			")->queryColumn();
			
			if (count($missingEmails) > 0)
			{
				$users = implode(', ', $missingEmails);
				$solutions[$i]['canbetaken'] = false;
				$solutions[$i]['message'] = sprintf(Yii::t('items','missing emails'), $users);
			}
			else
				$solutions[$i]['canbetaken'] = true;				
		}
		
		$userId = Yii::app()->user->getState('user_id');
		if ($userId > 0)
		{
			$command = Yii::app()->db->createCommand();
			$command->setText("update solution
					set `read` = 1
					where
							item_id = $itemId
							and exists (
								select 1
								from item i
								where
									i.user_id = $userId
									and i.id = $itemId
							)")->execute();
		}

		return $solutions;
	}
	
	public function loadComments($itemId)
	{
		$command = Yii::app()->db->createCommand();
		
		$comments = $command->setText("select ic.*, coalesce(u.real_name, u.username) as user_name
			from item_comment ic inner join user u on u.id = ic.user_id
			where ic.item_id = $itemId
			order by id
			")->queryAll();
		
		$userId = Yii::app()->user->getState('user_id');
		
		if ($userId > 0)
		{
			$command = Yii::app()->db->createCommand();
			$command->setText("delete from unread_comment
					where user_id = $userId
							and comment_id in (
								select c.id
								from item_comment c
								where c.item_id = $itemId
							)")->execute();
		}

		return $comments;
	}
	
	function addSessionItems()
	{
		$addItems = Yii::app()->user->getState('addItems');
		foreach($_POST as $value)
		{
			if (substr($value, 0, 5) == 'check')
			{
				$id =	substr($value, 5, strlen($value) - 5);
				$addItems[] = $id;
			}
		}
		Yii::app()->user->setState('addItems', $addItems);
	}
	
	function addItems($solutionId)
	{
		$addItems = Yii::app()->user->getState('addItems');
		if (isset($addItems))
		{
			$command = Yii::app()->db->createCommand();	
			foreach($addItems as $itemId)
			{
				$command->insert('solution_item', 
					array(
						'solution_id' => $solutionId,
						'item_id' => $itemId,
						));
			}
		}		
	}
	
	// ------------------
}
