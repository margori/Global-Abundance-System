<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	public function getId()
	{
		return $this->getState('user_id');
	}
					
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean$this whether authentication succeeds.
	 */
	public function authenticate()
	{
		$today = new DateTime();
		$lastLogin = $today->sub(new DateInterval('P6M'))->format('Y-m-d'); // Today plus 6 month

		$command = Yii::app()->db->createCommand();
		$command->delete('user', "last_login < '$lastLogin'");		
			
		$command = Yii::app()->db->createCommand();

		$row = $command->select("*")
						->from('user')
						->where(
										array('and', 'username = :username', 'password = :password'), 
										array( 
											"username" => $this->username,
											'password' => $this->password,							
										))
						->queryRow();

		if (isset($row))
		{
			$command = Yii::app()->db->createCommand();
			$userCount = $command->select('count(*)')
							->from('user')->where('id <> '. $row['id'])->queryScalar();
							
			$command = Yii::app()->db->createCommand();
			$brokenCount = $command->select('count(*)')
							->from('user_heart')->where('love = 0 and from_user_id <> '. $row['id']. ' and to_user_id = '. $row['id'])
							->queryScalar();
			
			if ($userCount < $brokenCount / 2)
			{
				Yii::app()->request->redirect(Yii::app()->createUrl('user/ban'));												
			}			

			$command = Yii::app()->db->createCommand();
			$command->update('user',array('last_login' => $today->format('Y-m-d')), 'id = :id', array(':id'=> $row['id']));
			
			$command = Yii::app()->db->createCommand();
			$command->delete('item', "creation_date < '$lastLogin'");		

			$this->errorCode=self::ERROR_NONE;
			$this->setState('user_id', $row['id']);
			$this->setState('user_real_name', $row['real_name'] ?: $row['username']);
			$this->setState('user_email', $row['email']);
			$this->setState('user_default_tags', $row['default_tags']);
			$this->setState('pageSize', 10);
			$this->setState('language', $row['language']);
		}
		else
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
	
		return !$this->errorCode;
	}
}