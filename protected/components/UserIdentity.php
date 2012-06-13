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
					
	public function getName()
	{
		return $this->getState('user_real_name');
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
		$command = Yii::app()->db->createCommand();

		$row = $command->select("*")
						->from('user')
						->where(
										// Operators
										array('and', 'username = :username', 'password = :password'), 
										// Parameters
										array( 
											"username" => $this->username,
											'password' => $this->password,							
										))
						->queryRow();

		if (isset($row))
		{
			$this->errorCode=self::ERROR_NONE;
			$this->setState('user_id', $row['id']);
			$this->setState('user_real_name', $row['real_name'] ?: $row['username']);
			$this->setState('user_default_tags', $row['default_tags']);
			$this->setState('pageSize', 10);
		}
		else
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
	
		return !$this->errorCode;
	}
}