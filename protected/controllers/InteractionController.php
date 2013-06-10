<?php

class InteractionController extends Controller
{
	public function actions()
	{
	}

	public function actionIndex()
	{
		if (Yii::app()->user->isGuest)
				$this->redirect(Yii::app()->createUrl('site'));
		
		$projects = ProjectModel::loadMyProject();
		
		$this->render('index',array(
				'projects' => $projects,
		));
	}
	
	public function actionSubmit()
	{
		$model= new ItemModel();

		if(isset($_POST['save']))
		{
			$model->description = strip_tags($_POST['description']);
			$model->shared = $_POST['shared'];
			$model->quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
			$sixMonthLater = new DateTime('+6 month');
			$sixMonthLater = $sixMonthLater->format('Y-m-d');
			$model->expiration_date = $sixMonthLater;
			$model->project_id = $_POST['project'];
							
			if($model->save())
			{
				if ($model->shared == 1)
					$this->redirect($this->createUrl("share/view/" . $model->id));				
				else
					$this->redirect($this->createUrl("need/view/" . $model->id));				
			}
		}
			
	}
}


?>