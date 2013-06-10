<?php
class UserModel extends CFormModel
{
	public $id;
	public $username;
	public $password;
	public $confirmation;
	
	public $realName;
	public $email;
	public $language;
	
	public $message;
	
	public $myLove;
	public $hisLove;
	
	public $about;
	
	public function rules()
	{
		return array(
			array('id, username, password, confirmation
				, realName, email, language, about','safe'),
		);
	}
	
	public function validate()
	{
	
	}
	
	public function validateSave()
	{
		$USERNAME_MIN_LENGTH = 4;
		
		$message = '';
				
		if (!isset($this->username) || $this->username == '')
			$message = Yii::t('register', 'username required');
		else	if (strlen($this->username) < $USERNAME_MIN_LENGTH)
			$message = sprintf(Yii::t('register', 'username too short'), $USERNAME_MIN_LENGTH);
		else 
		{		
			$command = Yii::app()->db->createCommand();
			$count = $command->select('count(*)')
							->from('user')
							->where(
										array('and', 'username = :username', 'id <> :id'), 
										// Parameters
										array( 
											"username" => $this->username,
											'id' => Yii::app()->user->getState('user_id'),							
										))
							->queryScalar();

			if ($count > 0)
				$message = Yii::t('register', 'username taken');
		}
		return $message;
	}
	
	function validateChangePassword()
	{
		$PASSWORD_MIN_LENGTH = 4;
		
		$message = '';
				
		if ($this->password != '')
		{
			if (!isset($this->password) || $this->password == '')
				$message = Yii::t('register', 'password required');
			else if (strlen($this->password) < $PASSWORD_MIN_LENGTH)
				$message = sprintf(Yii::t('register', 'password too short'), $PASSWORD_MIN_LENGTH);
			else if (!isset($this->confirmation) || $this->confirmation == '')
				$message = Yii::t('register', 'confirmation required');
			else if ($this->password != $this->confirmation)
				$message = Yii::t('register', 'password confirmation');
		}

		return $message;
	}

	function load($id)
	{
		$command = Yii::app()->db->createCommand();
		$data = $command->select()
						->from('user')
						->where('id = ' . $id)
						->queryRow();
		
		$this->id = $data['id'];
		$this->username = $data['username'];
		$this->realName = $data['real_name'];
		$this->email = $data['email'];
		$this->language = $data['language'];
		$this->about = $data['about'];
		
		$command = Yii::app()->db->createCommand();
		$love = $command->select('love')
						->from('user_heart')
						->where('from_user_id = :from and to_user_id = :to')
						->queryScalar(array(
								'from'=>$id,
								'to'=>Yii::app()->user->getState('user_id'),
						));
		$this->hisLove = $love == null ? 1 : $love;
		
		$command = Yii::app()->db->createCommand();
		$love = $command->select('love')
						->from('user_heart')
						->where('from_user_id = :from and to_user_id = :to')
						->queryScalar(array(
								'from'=>Yii::app()->user->getState('user_id'),
								'to'=>$id,
						));
		$this->myLove = $love == null ? 1 : $love;
	}

	public function save()
	{
		$userId = Yii::app()->user->getState('user_id');
		$command = Yii::app()->db->createCommand();
		
		$exists = 0;
		foreach (Yii::app()->params['languages'] as $iso => $language) 
			if ($iso == $this->language)
				$exists = 1;
		if ($exists == 0)
			$this->language = ConfigurationModel::instance()->default_language;
		
		$command->update('user', 
				array(
				'username'=>$this->username,
				'real_name' => $this->realName,
				'email' => $this->email,
				'language' => $this->language,
				'about' => $this->about,
				),
				'id = :userId', array(
						'userId' => $userId,
				));
		
		if ($this->realName)
			Yii::app()->user->setState('user_real_name', $this->realName);
		else
			Yii::app()->user->setState('user_real_name', $this->username);
		Yii::app()->user->setState('user_email', $this->email);
		Yii::app()->user->setState('language', $this->language);
	
		return true;
	}
	
	public function changePassword()
	{
		if (isset($this->password) && $this->password == '')
			return false;

		$userId = Yii::app()->user->getState('user_id');
		$command = Yii::app()->db->createCommand();

		$now = date('r');
		$salt = md5($now);
		$finalPassword = md5($salt . md5($this->password) );

		$command->update('user', 
				array(
				'password'=>$finalPassword,
				'password_salt'=>$salt,
				),
				'id = :userId', array(
						'userId' => $userId,
				));
		return true;
	}
	
	public function saveAbout()
	{
		$userId = Yii::app()->user->getState('user_id');
		$command = Yii::app()->db->createCommand();

		$command->update('user', 
				array(
				'about'=>  strip_tags($this->about),
				),
				'id = :userId', array(
						'userId' => $userId,
				));
		return true;
	}

	function browse($nameFilter, $pageCurrent = 1, $pageSize = 10)
	{
		$command = Yii::app()->db->createCommand();
		$offset = ($pageCurrent - 1) * $pageSize;
		$userId = Yii::app()->user->getState('user_id');
		
		$sql = 'select coalesce(u.real_name, u.username) as real_name, u.id ';
		if ($userId > 0)
			$sql .= ', coalesce(love_ab.love, 1) as love ';
		else
			$sql .= ', 1 as love ';
		$sql .= 'from user u ';
		if ($userId > 0)
		{
			$sql .= "left join user_heart love_ab on love_ab.from_user_id = $userId and love_ab.to_user_id = u.id ";
		}
	
		if (isset($nameFilter) && trim($nameFilter) != '')
		{
			$nameFilter = addslashes($nameFilter);
			$sql .= "having real_name like '%$nameFilter%' ";
		}
		
		$sql .= 'order by real_name ';
		$sql .= "limit $pageSize offset $offset";
		
		$items = $command->setText($sql)->queryAll();
		return $items;		
	}
	
	function browseCount($nameFilter)
	{
		$command = Yii::app()->db->createCommand();
		
		$sql = 'select count(*) ';
		$sql .= 'from user i ';
	
		return $command->setText($sql)->queryScalar();		
	}

	function delete($id)
	{
		if ($id != Yii::app()->user->getState('user_id'))
			return;
		
		$command = Yii::app()->db->createCommand();	
		
		$sql = "delete from user where id = $id";
		$command->setText($sql)->execute();		
		// Detail is deleted too due to foreign key cascade restriction
	}	
	
	function loadNeeds()
	{
		$command = Yii::app()->db->createCommand();
		
		$needs = $command->setText("select i.*
				from item i
				where i.shared = 0 and i.user_id = " . $this->id .
				' order by i.id desc')->queryAll();
		
		return $needs;

	}

	function loadShares()
	{
		$command = Yii::app()->db->createCommand();
		
		$shares = $command->setText("select i.*
				from item i
				where i.shared = 1 and i.user_id = " . $this->id .
				' order by i.id desc')->queryAll();
		
		return $shares;
	}
	
	function loadZones()
	{
		$command = Yii::app()->db->createCommand();
		
		$zones = $command->setText("select z.*
				from user_zone z
				where z.user_id = " . $this->id )->queryAll();
		
		return $zones;
	}
	
	static function newComments()
	{
		$userId = Yii::app()->user->getId();
		$command = Yii::app()->db->createCommand();
		$comments = $command->setText("SELECT 
				COALESCE( u.real_name, u.username ) AS user_name, 
				ic.item_id,
				i.shared
			FROM unread_comment uc
				INNER JOIN item_comment ic ON ic.id = uc.comment_id
				INNER JOIN user u ON u.id = ic.user_id
				inner join item i on i.id = ic.item_id
			WHERE uc.user_id = $userId")->queryAll();
		return $comments;
	}

	static function newSolutions()
	{
		$userId = Yii::app()->user->getId();
		$command = Yii::app()->db->createCommand();
		$count = $command->setText("select
						coalesce(u.real_name, u.username) as user_name,
						s.item_id	
			from solution s 
				inner join user u on u.id = s.user_id
				inner join item i on i.id = s.item_id
			where 
				i.user_id = $userId
				and i.shared = 0
				and s.read = 0
				and s.status = 2")
						->queryAll();
		return $count;
	}

	public function love($fromUserId, $toUserId, $love) 
	{
		$command = Yii::app()->db->createCommand();
		$command->delete('user_heart',
						"from_user_id = :from and to_user_id = :to",
						array(
								'from'=>$fromUserId,
								'to'=>$toUserId,
								));

		if($love != 1)
		{
			$command = Yii::app()->db->createCommand();
			$command->insert('user_heart', array(
					'from_user_id'=>$fromUserId,
					'to_user_id'=>$toUserId,
					'love'=>$love,
			));
		}		
	}
	
	public function saveZone($status, $id, $top, $right, $bottom, $left)
	{
		$command = Yii::app()->db->createCommand();
		if ($status == 'save' and $id == '')
			$command->insert('user_zone', array(
					'top'=>$top,
					'right'=>$right,
					'bottom'=>$bottom,
					'left'=>$left,
					'user_id' => Yii::app()->user->getId(),
			));
		else if ($status == 'save' and $id > 0)
			$command->update('user_zone', array(
					
					'top'=>$top,
					'right'=>$right,
					'bottom'=>$bottom,
					'left'=>$left,
			), 'id = :id', array('id' => $id));		
		else
			$command->delete ('user_zone', 'id = :id', array('id'=>$id));
	}
}