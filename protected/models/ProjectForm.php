<?php
class ProjectForm extends CFormModel
{
	public $id;
	public $name;
	public $description;
	public $user_id;
	public $hisLove;
	public $username;

	public function rules()
	{
		return array(
			array('id, name, description, user_id, username, hisLove', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>Yii::t('project', 'id'),
			'name'=>Yii::t('project', 'name'),
			'description'=>Yii::t('item', 'description'),
		);
	}
	
	public function browse($nameFilter, $tags, $options = '', $pageCurrent = 1, $pageSize = 10)
	{
		$command = Yii::app()->db->createCommand();
		$offset = ($pageCurrent - 1) * $pageSize;
		$userId = Yii::app()->user->getState('user_id');
		
		$sql = 'select p.id, p.name, p.description, p.user_id, coalesce(u.real_name, u.username) as user_name ';
		if ($userId > 0)
			$sql .= ', coalesce(love_ab.love, 1) as love ';
		else
			$sql .= ', 1 as love ';
		$sql .= 'from project p inner join user u on u.id = p.user_id ';
		if ($userId > 0)
		{
			$sql .= "left join user_heart love_ab on love_ab.from_user_id = $userId and love_ab.to_user_id = u.id ";
			$sql .= "left join user_heart love_ba on love_ba.from_user_id = p.user_id and love_ba.to_user_id = $userId ";
		}
	
		$sql .= "where 1 = 1 ";
		
		if (isset($nameFilter) && trim($nameFilter) != '')
		{
			$nameFilter = addslashes($nameFilter);
			$sql .= " and p.name like '%$nameFilter%' ";
		}
		
		if (substr_count($options,'mine') > 0 and $userId > 0)
			$sql .= " and p.user_id = $userId ";
	
		if (isset($tags) && trim($tags) != '')
		{
			$splitTags = explode(' ', $tags);
			foreach($splitTags as $splitTag)
			{
				if (substr($splitTag, 0, 1) == '+')
					$sql .= 'and p.description like \'%'.substr($splitTag, 1, strlen ($splitTag) - 1) .'%\' ';
				else
					$sql .= 'and p.description not like \'%'.substr($splitTag, 1, strlen ($splitTag) - 1) .'%\' ';				
			}
		}		

		if ($userId > 0)
			$sql .= 'and (love_ab.love > 0 or love_ab.love is null) and (love_ba.love > 0 or love_ba.love is null) ';
	
		$sql .= 'order by p.id desc ';
		$sql .= "limit $pageSize offset $offset";
		
		$projects = $command->setText($sql)->queryAll();
		return $projects;		
	}

	public function browseCount($nameFilter, $tags, $options = '')
	{
		$userId = Yii::app()->user->getState('user_id');

		$command = Yii::app()->db->createCommand();
		
		$sql = 'select count(*) ';
		$sql .= 'from project p inner join user u on u.id = p.user_id ';
		if ($userId > 0)
		{
			$sql .= "left join user_heart love_ab on love_ab.from_user_id = $userId and love_ab.to_user_id = u.id ";
		}

		$sql .= 'where 1 = 1 ';
		if (isset($nameFilter) && trim($nameFilter) != '')
		{
			$nameFilter = addslashes($nameFilter);
			$sql .= "and p.name like '%$nameFilter%' ";
		}
	
		if (isset($tags) && trim($tags) != '')
		{
			$splitTags = explode(' ', $tags);
			foreach($splitTags as $splitTag)
			{
				if (substr($splitTag, 0, 1) == '+')
					$sql .= 'and p.description like \'%'.substr($splitTag, 1, strlen ($splitTag) - 1) .'%\' ';
				else
					$sql .= 'and p.description not like \'%'.substr($splitTag, 1, strlen ($splitTag) - 1) .'%\' ';				
			}
		}
		
		$count = $command->setText($sql)->queryScalar();
		return $count;
	}
	
	public function load($id)
	{
		$command = Yii::app()->db->createCommand();
		$userId = Yii::app()->user->getState('user_id');
	
		$sql = 'select p.*, coalesce(u.real_name, u.username) as username ';
		if ($userId > 0)
			$sql .= ', coalesce(uh.love, 1) as hisLove ';
		else	
			$sql .= ', 1 as hisLove ';
		
		$sql .= 'from project p 
				inner join `user` u
					on u.id = p.user_id ';
		
		if ($userId > 0)
			$sql .= "left join user_heart uh
					on uh.from_user_id = p.user_id and uh.to_user_id = $userId ";
		
		$sql .= "where p.id = $id ";
		
		$row = $command->setText($sql)->queryRow();
		
		if($row === null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		$this->attributes = $row;
	}

	public function save()
	{
		$command = Yii::app()->db->createCommand();

		if (!isset($this->id))
		{
	 		$userId = Yii::app()->user->getState('user_id');
			$command->insert('project', array(
					'name' => $this->name,
					'description' => $this->description,
					'user_id' => $userId,
					));
			$this->id = $command->connection->lastInsertID;
		}
		else
		{
			$command->update('project', 
							array(
									'name' => $this->name,
									'description' => $this->description,
									), 
							'id = :id', array(':id'=> $this->id)
							);
		}		
		return true;
	}
	
	public function delete()
	{
		$command = Yii::app()->db->createCommand();
		$itemUserId = $command->select('user_id')
						->from('project')
						->where("id = " . $this->id)
						->queryScalar();
		
		if ($itemUserId != Yii::app()->user->getState('user_id'))
			return;

		$command = Yii::app()->db->createCommand();	
		$command->delete('project', 'id = :id', array(':id'=> $this->id));
		// Detail is deleted too due to foreign key cascade restriction
	}
	
	public static function loadMyProject()
	{
		$command = Yii::app()->db->createCommand();
		$userId = Yii::app()->user->getState('user_id');
		
		$sql = 'select p.id, p.name ';
		$sql .= 'from project p ';
		$sql .= "where p.user_id = $userId ";
		$sql .= 'order by p.name ';
		
		$rawProjects = $command->setText($sql)->queryAll();
		
		$projects[] = null;
		foreach($rawProjects as $rawProject)
			$projects[$rawProject['id']] = $rawProject['name'];

		return $projects;				
	}
	
	public function loadNeeds()
	{
		$command = Yii::app()->db->createCommand();
		
		$needs = $command->setText("select i.*
				from item i
				where i.shared = 0 and i.project_id = " . $this->id .
				' order by i.id desc')->queryAll();
		
		return $needs;

	}

	function loadShares()
	{
		$command = Yii::app()->db->createCommand();
		
		$shares = $command->setText("select i.*
				from item i
				where i.shared = 1 and i.project_id = " . $this->id .
				' order by i.id desc')->queryAll();
		
		return $shares;
	}
	
}
