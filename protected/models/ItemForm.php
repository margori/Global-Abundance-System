<?php
class ItemForm extends CFormModel
{
	public $id;
	public $description;
	public $original_description;
	public $shared;
	public $user_id;
	public $username;
	public $quantity;
	public $expiration_date;
	public $creation_date;
	public $hisLove;

	public function rules()
	{
		return array(
			array('id, shared, description, quantity, expiration_date, creation_date', 'required'),
			array('username, user_id, original_description, hisLove', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>Yii::t('items', 'id'),
			'description'=>Yii::t('items', 'description'),
			'quantity'=>Yii::t('items', 'quantity'),
		);
	}
	
	public function browse($tags,$shared = 1, $options = '', $pageCurrent = 1, $pageSize = 10, $includedUserId = null)
	{
		$command = Yii::app()->db->createCommand();
		$offset = ($pageCurrent - 1) * $pageSize;
		$userId = Yii::app()->user->getState('user_id');
		
		$sql = "select i.*, coalesce(u.real_name, username) as user_name ";
		if ($userId > 0)
			$sql .= ', love_ab.love ';
		else
			$sql .= ', 1 as love ';
		$sql .= 'from item i inner join user u on u.id = i.user_id ';
		if ($userId > 0)
		{
			$sql .= "left join user_heart love_ab on love_ab.from_user_id = $userId and love_ab.to_user_id = i.user_id ";
			$sql .= "left join user_heart love_ba on love_ba.from_user_id = i.user_id and love_ba.to_user_id = $userId ";
		}
		if ($includedUserId > 0)
		{
			$sql .= "left join user_heart love_ac on love_ac.from_user_id = $userId and love_ac.to_user_id = $includedUserId ";
			$sql .= "left join user_heart love_ca on love_ca.from_user_id = $includedUserId and love_ca.to_user_id = $userId ";
			$sql .= "left join user_heart love_bc on love_bc.from_user_id = i.user_id and love_bc.to_user_id = $includedUserId ";
			$sql .= "left join user_heart love_cb on love_cb.from_user_id = $includedUserId and love_cb.to_user_id = i.user_id ";
		}
		$sql .= 'where i.quantity > 0 ';
		$sql .= "and i.expiration_date >= curdate() ";
		$sql .= "and i.shared = $shared ";
		if ($userId > 0)
			$sql .= 'and (love_ab.love > 0 or love_ab.love is null) and (love_ba.love > 0 or love_ba.love is null) ';
		if ($includedUserId > 0)
		{
			$sql .= 'and (love_ac.love > 0 or love_ac.love is null) and (love_ca.love > 0 or love_ca.love is null) ';
			$sql .= 'and (love_bc.love > 0 or love_bc.love is null) and (love_cb.love > 0 or love_cb.love is null) ';
		}
		if (isset($tags) && trim($tags) != '')
		{
			$splitTags = explode(' ', $tags);
			foreach($splitTags as $splitTag)
			{
				if (substr($splitTag, 0, 1) == '+')
					$sql .= 'and i.description like \'%'.substr($splitTag, 1, strlen ($splitTag) - 1) .'%\' ';
				else
					$sql .= 'and i.description not like \'%'.substr($splitTag, 1, strlen ($splitTag) - 1) .'%\' ';				
			}
		}
		
		if ($options != '')
		{
			$sql .= 'and ( 1 = 0 ';

			if (substr_count($options,'newItem') > 0)
				$sql .= "or not exists(select 1 from solution s where s.item_id = i.id) ";
			if (substr_count($options,'draftSolutions') > 0)
				$sql .= "or exists(select 1 from solution s where s.status = 1 and s.item_id = i.id) ";
			if (substr_count($options,'mine') > 0 and $userId > 0)
				$sql .= "or i.user_id = $userId ";

			$sql .= ')';
		}
			
		$sql .= 'order by id desc ';
		$sql .= "limit $pageSize offset $offset";
		
		$items = $command->setText($sql)->queryAll();
			return $items;
		return $items;
	}

	public function browseCount($tags,$shared = 1, $options = '')
	{
		$userId = Yii::app()->user->getState('user_id');

		$command = Yii::app()->db->createCommand();
		
		$sql = 'select count(*) ';
		$sql .= 'from item i ';
		if ($userId > 0)
		{
			$sql .= "left join user_heart uh on uh.from_user_id = i.user_id and uh.to_user_id = $userId ";
			$sql .= "left join user_heart myuh on myuh.to_user_id = i.user_id and myuh.from_user_id = $userId ";
		}
		
		$sql .= 'where i.quantity > 0 ';
		$sql .= "and i.expiration_date >= curdate() ";
		$sql .= "and i.shared = $shared ";
		if ($userId > 0)
			$sql .= 'and (uh.love > 0 or uh.love is null) and (myuh.love > 0 or myuh.love is null) ';
		if (isset($tags) && trim($tags) != '')
		{
			$splitTags = explode(' ', $tags);
			foreach($splitTags as $splitTag)
			{
				if (substr($splitTag, 0, 1) == '+')
					$sql .= 'and i.description like \'%'.substr($splitTag, 1, strlen ($splitTag) - 1) .'%\' ';
				else
					$sql .= 'and i.description not like \'%'.substr($splitTag, 1, strlen ($splitTag) - 1) .'%\' ';				
			}
		}
		
		if ($options != '')
		{
			$sql .= 'and ( 1 = 0 ';

			if (substr_count($options,'newItem') > 0)
				$sql .= "or not exists(select 1 from solution s where s.item_id = i.id) ";
			if (substr_count($options,'draftSolutions') > 0)
				$sql .= "or exists(select 1 from solution s where s.status = 1 and s.item_id = i.id) ";
			if (substr_count($options,'mine') > 0 and $userId > 0)
				$sql .= "or i.user_id = $userId ";

			$sql .= ')';
		}
		
		$count = $command->setText($sql)->queryScalar();
		return $count;
	}

	public function save()
	{
		$command = Yii::app()->db->createCommand();
		
		if (!isset($this->id))
		{
	 		$userId = Yii::app()->user->getState('user_id');
			$command->insert('item', array(
					'description' => $this->description,
					'original_description' => $this->description,
					'shared' => $this->shared,
					'user_id' => $userId,
					'quantity' => $this->quantity,							
					'expiration_date' => $this->expiration_date,	
					'creation_date' => $this->creation_date,
					));
			$this->id = $command->connection->lastInsertID;
		}
		else
		{
			$command->update('item', 
							array(
									'description' => $this->description,
									'shared' => $this->shared,
									'quantity' => $this->quantity,							
									'expiration_date' => $this->expiration_date,							
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
						->from('item')
						->where("id = " . $this->id)
						->queryScalar();
		
		if ($itemUserId != Yii::app()->user->getState('user_id'))
			return;

		$command = Yii::app()->db->createCommand();	
		$command->delete('item', 'id = :id', array(':id'=> $this->id));
		// Detail is deleted too due to foreign key cascade restriction
	}
	
	public function newSolution($itemId)
	{
		$userId = Yii::app()->user->getState('user_id');
		$command = Yii::app()->db->createCommand();	
		$command->insert('solution', 
				array(
					'item_id' => $itemId,
					'user_id' => $userId,
					));
	}

	public function deleteSolution($solutionId)
	{
		$command = Yii::app()->db->createCommand();
		$commentUserId = $command->select('user_id')
						->from('solution')
						->where("id = $solutionId")
						->queryScalar();
		
		if ($commentUserId != Yii::app()->user->getState('user_id'))
			return;

		$command = Yii::app()->db->createCommand();	
		
		$command->delete('solution', 
				'id = :id', array(':id' => $solutionId)
				);
		// Detail is deleted too due to foreign key cascade restriction
	}
	
	public function deleteSolutionItem($id)
	{
		$command = Yii::app()->db->createCommand();	
		
		$command->delete('solution_item', 
				'id = :id', array(':id' => $id)
				);
	}
	
	public function markAsDraft($solutionId)
	{
		$command = Yii::app()->db->createCommand();	
		$command->update('solution', 
							array(
									'status' => 1,
									), 
							'id = :id', array(':id'=> $solutionId)
							);		
	}
	
	public function markAsComplete($solutionId)
	{
		$previousStatus = Yii::app()->db->createCommand()
						->setText('select status from solution where id = '.$solutionId)
						->queryScalar();

		// Mark as complete
		$command = Yii::app()->db->createCommand();	
		$command->update('solution', 
							array('status' => 2,'read'=>''), 
							'id = :id', array(':id'=> $solutionId)
							);
		
		//Check for notification
		$needId = Yii::app()->db->createCommand()
						->setText('select s.item_id 
							from solution s where s.id = '.$solutionId)
						->queryScalar();
		
		$alreadyNotified = Yii::app()->db->createCommand()
						->setText('select i.notified 
							from item i where i.id = '.$needId)
						->queryScalar();
		
		if ($alreadyNotified > 0)
			return;
				
		if ($previousStatus > 1)
			return;
		
		// Notify
		$need = Yii::app()->db->createCommand()
					->setText('select u.email, coalesce(u.real_name, u.username) as user_name, i.id as need_id 
							from user u 
								inner join item i 
									on i.user_id = u.id
							where 
								i.id = '.$needId)
					->queryRow();
		
		$needUrl = 'http://'.$_SERVER["HTTP_HOST"] . Yii::app()->baseUrl . '/index.php/need/view/' . $need['need_id'];

		$subjectTemplate = Yii::t('interaction', 'subject complete template');
		$headerTemplate = Yii::t('interaction', 'header template');
		$completeTemplate = Yii::t('interaction', 'complete template');
		$footerTemplate = Yii::t('interaction', 'footer template');


		$mailHeader = sprintf($headerTemplate, $need['user_name']). "\n\n";
		$mailBody = sprintf($completeTemplate , $needUrl) . "\n\n";
		$mailFooter = $footerTemplate;

		$mail = $mailHeader . $mailBody . $mailFooter;

		$this->mailTo($need['email'], $need['user_name'], $subjectTemplate, $mail);
		
		Yii::app()->db->createCommand()
					->setText('update item
						set notified = 1
						where id = '.$needId)
					->execute();
	}
	
	public function take($solutionId)
	{
		$notified = array();
		
		$command = Yii::app()->db->createCommand();		
		$needId = $command->select('item_id')
						->from('solution')
						->where ('id = ' . $solutionId)
						->queryScalar();
		
		if (!isset($needId))
			return;
		
		// Update quantities to need
		$command = Yii::app()->db->createCommand();		
		$command->setText("update item set quantity = quantity - 1 where id = $needId")->execute();

		$command = Yii::app()->db->createCommand();		
		$need = $command->select('i.description, coalesce(u.real_name, u.username) as user_name, u.email')
						->from('item i` inner join `user` `u` on `u.id` = `i.user_id')
						->where('i.id = '.$needId)
						->queryRow();
		
		$command = Yii::app()->db->createCommand();		
		$solution = $command->setText("select s.*, coalesce(u.real_name, u.username) as user_name, u.email						
				from solution s inner join user u on u.id = s.user_id
				where s.id = $solutionId")
				->queryRow();

		$command = Yii::app()->db->createCommand();		
		$solutionItems = $command->setText('select 
				i.id, i.description, coalesce(u.real_name, u.username) as user_name, u.email
			from item i	
				inner join solution_item si on si.item_id = i.id
				inner join user u on u.id = i.user_id
			where si.solution_id = ' . $solutionId)
						->queryAll();
		
		$subjectTemplate = Yii::t('interaction', 'subject taken template');
		$headerTemplate = Yii::t('interaction', 'header template');
		$takeTemplate = Yii::t('interaction', 'take template');
		$needTemplate = Yii::t('interaction', 'need template');
		$solutionTemplate = Yii::t('interaction', 'solution template');
		$solutionItemTemplate = Yii::t('interaction', 'solution item template');
		$footerTemplate = Yii::t('interaction', 'footer template');
	
		$needDescription = sprintf($needTemplate, 
				$need['user_name'],
				$need['email'],
				$need['description']
		). "\n";

		$solutionDescription = sprintf($solutionTemplate, 
				$solution['user_name'],
				$solution['email']
		). "\n";

		$solutionItemsDescription = '';
		foreach($solutionItems as $solutionItem)
		{
			$command = Yii::app()->db->createCommand();		
			$command->setText("update item set quantity = quantity - 1 where id = " . $solutionItem['id'])->execute();
	
			$solutionItemDescription = sprintf($solutionItemTemplate,
				$solutionItem['user_name'],
				$solutionItem['email'],
				$solutionItem['description']);
			$solutionItemsDescription .= '- ' . $solutionItemDescription . "\n";
		}
		
		// Send mails		
		// 
		// Need mail
		$mailHeader = sprintf($headerTemplate, $need['user_name']). "\n";
		$mailBody = sprintf($takeTemplate , $needDescription."\n".$solutionDescription.$solutionItemsDescription). "\n";
		$mailFooter = $footerTemplate;
		
		$mail = $mailHeader . $mailBody . $mailFooter;
				
		$this->mailTo($need['email'], $need['user_name'], $subjectTemplate, $mail);
		$notified[] = $need['email'];
		
		//Solution mail
		$mailHeader = sprintf($headerTemplate, $solution['user_name']). "\n";		
		$mail = $mailHeader . $mailBody . $mailFooter;
		
		$alreadyNotified = false;
		foreach($notified as $m)
			if ($m == $solution['email'])
				$alreadyNotified = true;

		if (!$alreadyNotified)
		{
			$this->mailTo($solution['email'], $solution['user_name'], $subjectTemplate, $mail);
			$notified[] = $solution['email'];
		}
				
		//Solution mails
		foreach($solutionItems as $solutionItem)
		{
			$mailHeader = sprintf($headerTemplate, $solutionItem['user_name']);
			$mail = $mailHeader. "\n" . $mailBody . "\n" . $mailFooter;
			
			$alreadyNotified = false;
			foreach($notified as $m)
				if ($m == $solutionItem['email'])
					$alreadyNotified = true;
				
			if (!$alreadyNotified)
			{
				$this->mailTo($solutionItem['email'], $solutionItem['user_name'], $subjectTemplate, $mail);
				$notified[] = $solutionItem['email'];
			}
		}
		
		//Archive solution
		$archiveDescrition = Yii::t('interaction','date') . ':' . date('Y-m-d') ."\n"
			. $need['user_name']. ' ' . Yii::t('interaction','needed') . "\n"
			. $need['description'] . "\n\n"
			. $solution['user_name'] . ' ' . Yii::t('interaction','proposed') . "\n"			
			;
		foreach($solutionItems as $solutionItem)
		{
			$archiveDescrition .= '- ' . $solutionItem['user_name'] . ' ' . Yii::t('interaction', 'shared'). ' '
				. $solutionItem['description'] . "\n";
		}
		Yii::app()->db->createCommand()->insert('solution_archive', array('description'=>$archiveDescrition));
				
		// Delete fulfilled need
		$itemForm = new Itemform();
		$itemForm->id = $needId;
		$itemForm->delete();

		// Roll back solutions which items are no longer available
		$command = Yii::app()->db->createCommand();		
		$command->setText("update solution set status = 1 
			where status =  2 and id in (
				select si.solution_id
				from solution_item si
					inner join item i
						on i.id = si.item_id
				where i.quantity < 1
			)")->execute();

		// Delete unavailable items. This may be long.
		$command = Yii::app()->db->createCommand();		
		$command->setText("delete from item where quantity < 1")->execute();
	}

	function mailTo($toEmail, $toName, $subject, $body)
	{
		if (Yii::app()->params['notify emails'] != 'yes')
			return;
		if (!isset($toEmail))
			return;

		Yii::import('application.extensions.phpmailer.JPhpMailer');
		$mail = new JPhpMailer();
		$mail->IsSMTP();
		$mail->Timeout = Yii::app()->params['smtp_timeout'];
		$mail->Host = Yii::app()->params['smtp_server'];
		$mail->Port = Yii::app()->params['smtp_port'];
		$mail->Username = Yii::app()->params['smtp_username'];
		$mail->Password = Yii::app()->params['smtp_password'];
		$mail->From = Yii::app()->params['smtp_from_email'];
		$mail->FromName = Yii::app()->params['smtp_from_name'];
		$mail->Subject = $subject;
		$mail->Body = utf8_decode($body);
		$mail->AddAddress($toEmail, $toName);
		$mail->IsHTML(false);
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = Yii::app()->params['smtp_secure'];;
		$result = $mail->Send();
		$message = $mail->ErrorInfo;
		return $result;
	}
	
	function comment($itemId, $userId, $comment)
	{
		$command = Yii::app()->db->createCommand();
		$command->insert('item_comment', array(
				'item_id' => $itemId,
				'user_id' => $userId, 
				'comment' => $comment
				));
		$commentId = $command->connection->pdoInstance->lastInsertId();
		
		// Notifying other users.
		$command = Yii::app()->db->createCommand();
		$command->setText("insert into unread_comment
			(user_id, comment_id)
					
			select i.user_id, $commentId
			from item i
			where i.id = $itemId

			union

			select s.user_id, $commentId
			from solution s
			where s.item_id = $itemId

			union

			select c.user_id, $commentId
			from item_comment c
			where c.item_id = $itemId						
			")->execute();
 		
	}

	function deleteComment($id)
	{
		$command = Yii::app()->db->createCommand();
		$commentUserId = $command->select('user_id')
						->from('item_comment')
						->where("id = $id")
						->queryScalar();
		
		if ($commentUserId != Yii::app()->user->getState('user_id'))
			return;
		
		$command = Yii::app()->db->createCommand();
		$command->delete('item_comment',	"id = $id");
	}

	public static function sharpTags($tags)
	{
		$allowed = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_ "; 

		$tags = explode(' ', $tags);
		$sharpTags = '';
		foreach($tags as $tag)
		{
			$trimedTag = trim($tag);
			if (isset($trimedTag) && $trimedTag != '')
			{
				for ($i=0; $i<strlen($trimedTag); $i++)
				{
					if ($i == 0)
					{
						if (substr($trimedTag,$i,1) !== '-' && substr($trimedTag,$i,1) !== '+' )
							$sharpTags .= '+';
						else
						{
							$sharpTags .= substr($trimedTag,$i,1);
							$i++;
						}
						$sharpTags .= '#';
						if (substr($trimedTag,$i,1)== '#')
							$i++;
					}
				
					$char = substr($trimedTag,$i,1);
					if (strpos($allowed, $char) > -1)
						$sharpTags .= $char; 
				}
			$sharpTags .= ' ';
			}
		}
		$sharpTags = rtrim($sharpTags);
		return $sharpTags;
	}
	
	public static function GetUserId($itemId)
	{
		$command = Yii::app()->db->createCommand();
		return $command->select('user_id')
						->from('item')
						->where("id = :id")
						->queryScalar(array('id'=>$itemId));
	}
}
