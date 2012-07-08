<?php

class ShareController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionNew()
	{
		$model = new ItemForm();
		$model->shared = 1;
		$model->quantity = 1;
		$model->description = Yii::app()->user->getState('user_default_tags');
		$today = new DateTime();
		$model->expiration_date = $today->add(new DateInterval('P6M'))->format('Y-m-d'); // Today plus 6 month

		// Uncomment the following line if AJAX validation is Shareed
		// $this->performAjaxValidation($model);

		if(isset($_POST['save']))
		{
			$model->attributes=$_POST['ItemForm'];
			$model->description=  strip_tags($model->description);
			$itemId = $model->save();
			if($itemId > 0)
				$this->redirect($this->createUrl("share/view/$itemId"));				
		}

		$this->render('new',array(
				'model'=>$model,
		));
	}

	public function actionEdit($id)
	{
		$model= $this->loadShare($id);

		if(isset($_POST['save']))
		{
			$model->attributes=$_POST['ItemForm'];
			$model->description=  strip_tags($model->description);
			if($model->save())
				$this->redirect($this->createUrl('share/view/'. $id));
		}
		
		if(isset($_POST['cancel']))
		{
			$this->redirect($this->createUrl('share/view/'. $id));
		}
		
		$this->render('edit',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$itemForm = new ItemForm();
		$itemForm->id = $id;
		$itemForm->delete();
		$this->redirect(Yii::app()->createUrl("./share"));
	}

	public function actionIndex()
	{
		$model = new ItemForm();

		if (isset($_GET['o']))
			Yii::app()->user->setState('share options',$_GET['o']);

		if (isset($_POST['filter']))
		{
			Yii::app()->user->setState('pageCurrent', 1);

			$options = '';
			if (isset($_POST['mine']))
				$options .= 'mine';
			
			Yii::app()->user->setState('share options',$options);

			$sharpTags = ItemForm::sharpTags($_POST['tags']);
			Yii::app()->user->setState('share tags', $sharpTags);
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

		$tags = Yii::app()->user->getState('share tags');		
		$shareCount = $model->browseCount($tags, 1, $options);  
		$pageCount = ceil($shareCount / $pageSize);
		if ($pageCurrent > $pageCount)
		{
			Yii::app()->user->setState('pageCurrent', 1);
			$pageCurrent = 1;
		}

		$shares = $model->browse($tags, 1, $options, $pageCurrent, $pageSize);

		$this->render('index',array(
			'items'=>$shares,
			'tags'=> $tags,				
			'options'=> $options,
			'pageCurrent' => $pageCurrent,
			'pageSize' => $pageSize,
			'pageCount' => $pageCount,
		));
	}
	
	public function actionView($id)
	{
		$userId = Yii::app()->user->getState('user_id');
		$share = $this->loadShare($id);
		$comments = $this->loadComments($id);

		$this->render('view',array(
			'share'=>$share,
			'comments' => $comments,
			'userId' => $userId,
			));
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
		$this->redirect(Yii::app()->createUrl("share/view/$id"));
	}
	
	public function actionDeleteComment($id, $returnId)
	{
		$itemForm = new ItemForm();
		$itemForm->deleteComment($id);
		$this->redirect(Yii::app()->createUrl("share/view/$returnId"));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadShare($id)
	{
		$command = Yii::app()->db->createCommand();
		
		$row = $command->setText("select i.*, coalesce(u.real_name, u.username) as username
			from item i inner join user u on u.id = i.user_id
			where i.id = $id")->queryRow();
		
		if($row === null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		$model = new ItemForm();
		$model->attributes = $row;
		return $model;
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
		if($userId > 0)
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

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='item-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
