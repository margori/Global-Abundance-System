<?php

class InteractionController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
	}

	public function actionIndex()
	{
		if (Yii::app()->user->isGuest)
				$this->redirect(Yii::app()->createUrl('site'));
		
		$this->render('index',array());
	}

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
	
	public function actionSubmit()
	{
		$model= new ItemForm();

		if(isset($_POST['save']))
		{
			$model->description = strip_tags($_POST['description']);
			$model->shared = $_POST['shared'];
			$model->quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
			$sixMonthLater = new DateTime('+6 month');
			$sixMonthLater = $sixMonthLater->format('Y-m-d');
			$model->expiration_date = $sixMonthLater;
							
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