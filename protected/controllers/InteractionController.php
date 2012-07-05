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
		
		$message = Yii::app()->user->getState('thanks_message');
		Yii::app()->user->setState('thanks_message', null);
		$this->render('index',array(
				'message' => $message,
				'defaultTags' => Yii::app()->user->getState('user_default_tags'),
				));
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
			$model->quantity = $_POST['quantity'] ?: 1;
			$today = new DateTime();
			$model->expiration_date = $today->add(new DateInterval('P6M'))->format('Y-m-d'); // Today plus 6 month
						
			if($model->save())
			{
				if ($model->shared == 1)
					Yii::app()->user->setState('thanks_message', Yii::t('interaction', 'thanks for sharing'));
				else
					Yii::app()->user->setState('thanks_message', Yii::t('interaction', 'thanks for asking'));
				$this->redirect(Yii::app()->createUrl('interaction'));
			}
		}
			
	}
}