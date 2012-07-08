<?php
class UserForm extends CFormModel
{
	public $id;
	public $username;
	public $password;
	public $confirmation;
	
	public $realName;
	public $email;
	public $defaultTags;
	public $language;
	
	public function rules()
	{
		return array(
			array('id, username, password, confirmation
				, realName, email, defaultTags, language','safe'),
		);
	}
	
	public function validate()
	{
		
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
		$this->defaultTags = $data['default_tags'];
		$this->language = $data['language'];
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
			$this->language = Yii::app()->params['default language'];
		
		if (isset($this->password) && $this->password != '')
			$command->update('user', 
					array(
					'username'=>$this->username,
					'password'=>$this->password,
					'real_name' => $this->realName,
					'email' => $this->email,
					'default_tags' => $this->defaultTags,
					'language' => $this->language,
					),
					'id = :userId', array(
							'userId' => $userId,
					));
		else
			$command->update('user', 
					array(
					'username'=>$this->username,
					'real_name' => $this->realName,
					'email' => $this->email,
					'default_tags' => $this->defaultTags,
					'language' => $this->language,
					),
					'id = :userId', array(
							'userId' => $userId,
					));
		
		if ($this->realName)
			Yii::app()->user->setState('user_real_name', $this->realName);
		else
			Yii::app()->user->setState('user_real_name', $this->username);
		Yii::app()->user->setState('user_default_tags', $this->defaultTags);
		Yii::app()->user->setState('user_email', $this->email);
		Yii::app()->user->setState('language', $this->language);
	
		return true;
	}
	
	function browse($nameFilter, $pageCurrent = 1, $pageSize = 10)
	{
		$command = Yii::app()->db->createCommand();
		$offset = ($pageCurrent - 1) * $pageSize;
		
		$sql = 'select coalesce(i.real_name, i.username) as real_name, i.id ';
		$sql .= 'from user i ';
	
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
				where i.shared = 0 and i.user_id = " . $this->id)->queryAll();
		
		return $needs;

	}

	function loadShares()
	{
		$command = Yii::app()->db->createCommand();
		
		$shares = $command->setText("select i.*
				from item i
				where i.shared = 1 and i.user_id = " . $this->id)->queryAll();
		
		return $shares;
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
}
