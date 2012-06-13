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

	public function rules()
	{
		return array(
			array('id, username, password, confirmation
				, realName, email, defaultTags','safe'),
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
	}

	public function save()
	{
		$userId = Yii::app()->user->getState('user_id');
		$command = Yii::app()->db->createCommand();
		
		if (isset($this->password) && $this->password != '')
			$command->update('user', 
					array(
					'username'=>$this->username,
					'password'=>$this->password,
					'real_name' => $this->realName,
					'email' => $this->email,
					'default_tags' => $this->defaultTags,
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
					),
					'id = :userId', array(
							'userId' => $userId,
					));
		
		Yii::app()->user->setState('user_real_name', $this->realName ?: $this->username);
		Yii::app()->user->setState('user_default_tags', $this->defaultTags);
	
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
}
