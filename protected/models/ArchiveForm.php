<?php
class ArchiveForm extends CFormModel
{
	public function rules()
	{
		return array();
	}
	
	public function validate()
	{
		
	}

	function load($id)
	{
	}

	public function save()
	{
	}
	
	function browse($tags, $pageCurrent = 1, $pageSize = 10)
	{
		$command = Yii::app()->db->createCommand();
		$offset = ($pageCurrent - 1) * $pageSize;
		
		$sql = 'select * ';
		$sql .= 'from solution_archive i  where 1 = 1 ';
	
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
		
		$sql .= 'order by id desc ';
		$sql .= "limit $offset, $pageSize ";
		
		$archives = $command->setText($sql)->queryAll();
		return $archives;		
	}
	
	function browseCount($tags)
	{
		$command = Yii::app()->db->createCommand();
		
		$sql = 'select count(*) ';
		$sql .= 'from solution_archive i where 1 = 1 ';
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
	
		return $command->setText($sql)->queryScalar();		
	}

	function delete($id)
	{
	}	
}
