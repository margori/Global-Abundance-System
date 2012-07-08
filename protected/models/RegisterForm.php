<?php
class RegisterForm extends CFormModel
{
	public $username;
	public $password;
	public $confirmation;
	public $language;

	public function rules()
	{
		return array(
			// username and password are required
			array('username, password, confirmation, language', 'required'),
			// rememberMe needs to be a boolean
			array('clause1, clause2, clause3, clause4, clause5','boolean'),
			// password needs to be authenticated
			array('password', 
				'compare', 
				'compareAttribute'=>'confirmation',
				'on'=>'register',
				'message'=>Yii::t('register','confirmationMismatch')),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>Yii::t('register', 'username'),
			'password'=>Yii::t('register', 'password'),
			'confirmation'=>Yii::t('register', 'confirmation'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function register()
	{
		$command = Yii::app()->db->createCommand();
		$command->insert('user', array(
				'username'=>$this->username,
				'password'=>$this->password,
				'language'=>$this->language,
				));
		
		$userId = $command->connection->lastInsertID;
		Yii::app()->user->setState('user_id', $userId);
		Yii::app()->user->setState('user_real_name', $this->username);		

		$model = new LoginForm;
		$model->username = $this->username;
		$model->password = $this->password;
		
		return $model->validate() && $model->login();
	}
	
	public function customValidate()
	{
		$USERNAME_MIN_LENGTH = 4;
		$PASSWORD_MIN_LENGTH = 4;
		
		
		if (!isset($this->username) || $this->username == '')
			return Yii::t('register', 'username required');
		if (strlen($this->username) < $USERNAME_MIN_LENGTH)
			return sprintf(Yii::t('register', 'username too short'), $USERNAME_MIN_LENGTH);
		if (!isset($this->password) || $this->password == '')
			return Yii::t('register', 'password required');
		if (strlen($this->password) < $PASSWORD_MIN_LENGTH)
			return sprintf(Yii::t('register', 'password too short'), $PASSWORD_MIN_LENGTH);
		if (!isset($this->confirmation) || $this->confirmation == '')
			return Yii::t('register', 'confirmation required');
		if ($this->password != $this->confirmation)
			return Yii::t('register', 'password confirmation');
		
		$command = Yii::app()->db->createCommand();
		$count = $command->select('count(*)')
						->from('user')
						->where('username = \'' . $this->username .'\'')
						->queryScalar();
		
		if ($count > 0)
			return Yii::t('register', 'username taken');
		
		return '';
	}
}
