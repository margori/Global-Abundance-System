<?php

class RegisterController extends Controller
{
	public function actions()
	{
		return array(
		);
	}

	public function actionIndex()
	{
		$model = new RegisterModel();
		
		$this->render('index',array(
				'model'=>$model, 'message'=>''
				));
	}
	
	public function actionRegister()
	{
		$model= new RegisterModel();

		$message ='';
		if(isset($_POST['register']))
		{
			$model->attributes=$_POST;
			
			$message = $model->customValidate();
			if($message == '')
				if ($model->register())
				$this->redirect(Yii::app()->createUrl('interaction'));
		}

		$model->password = null;
		$model->confirmation = null;
		$this->render('index',array(
				'model'=>$model,
				'message'=>$message,
				));
		
	}
	
	public function actionForgot()
	{
		$message = '';
		if(isset($_POST['remember']))
		{
			$email = addslashes($_POST['email']);
			
			$data = Yii::app()->db->createCommand()
						->setText("select u.id,  coalesce(u.real_name, u.username) as user_name, u.password_salt from user u where email = '$email'")
						->queryRow();
			
			if ($data)
			{
				$now = date('r');
				$salt = md5($now);
				
				$expiration = new DateTime('+24 hour');
				$expiration = $expiration->format('Y-m-d h:i:s');
			
				Yii::app()->db->createCommand()
					->update('user', 
							array(
									'reset_password_code' => $salt,
									'reset_password_expiration' => $expiration,
									), 
							'id = :id', array(':id'=> $data['id'])
							);

				$rememberUrl = 'http://'.$_SERVER["HTTP_HOST"] . Yii::app()->baseUrl . '/index.php/register/reset/' . $salt;

				$subjectTemplate = Yii::t('register', 'subject remember');
				$headerTemplate = Yii::t('interaction', 'header template');
				$bodyTemplate = Yii::t('register', 'body remember');
				$footerTemplate = Yii::t('interaction', 'footer template');

				$mailHeader = sprintf($headerTemplate, $data['user_name']). "\n\n";
				$mailBody = sprintf($bodyTemplate , $rememberUrl) . "\n\n";
				$mailFooter = $footerTemplate;

				$mail = $mailHeader . $mailBody . $mailFooter;

				ItemModel::mailTo($email, $data['user_name'], $subjectTemplate, $mail);

				$this->redirect(Yii::app()->createUrl('site'));
			}
			else
				$message = Yii::t('register','no email');
		}
		
		$this->render('forgot',array( 'message' => $message ));
	}
	
	public function actionReset($data)
	{
		$message = '';
		$code = addslashes($data);
		
		$row = Yii::app()->db->createCommand()
			->setText("select u.id from user u where u.reset_password_code = '$code'")
			->queryRow();
			
		if (!$row)
				$this->redirect(Yii::app()->createUrl('site'));

		if(isset($_POST['reset']))
		{
			$model= new RegisterModel();
			$model->username = 'ABCD1234()'; // Dummy data.
			$model->password = $_POST['password'];
			$model->confirmation = $_POST['confirmation'];
			$message = $model->customValidate();
			
			if ($message == '')
			{
				$now = date('r');
				$salt = md5($now);
				$finalPassword = md5($salt . md5($model->password) );

				Yii::app()->db->createCommand()
					->update('user', 
							array(
									'password' => $finalPassword,
									'password_salt' => $salt,
									'reset_password_code' => null,
									'reset_password_expiration' => null,
							), 
							'id = :id', array(':id'=> $row['id'])
							);
				$this->redirect(Yii::app()->createUrl('site'));
			}
		}

		$this->render('reset',array( 'code' => $code, 'message' => $message ));
	}
}

?>
