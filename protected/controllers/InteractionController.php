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
			$model->description = $_POST['description'];
			$model->shared = $_POST['shared'];
			$model->quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
			$today = new DateTime();
			$model->creation_date = $today->format('Y-m-d');
			$model->expiration_date = $today->add(new DateInterval('P6M'))->format('Y-m-d'); // Today plus 6 month
						
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