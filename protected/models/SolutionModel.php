<?php
class SolutionModel extends CFormModel
{
	public $id;
	public $status;
	public $user_id;
	public $canbetaken;
	public $message;
	public $item_id;
	public $user_name;
	
	public $items;
	
	public function rules()
	{
		return array(
			array('id', 'required'),
			array('status, user_id, item_id, canbetaken, message, user_name', 'safe'),
		);
	}
		
	public function load($id)
	{
		$command = Yii::app()->db->createCommand();
		$sql = "select *, coalesce(u.real_name, u.username) as user_name ";
		
		$sql .= " from solution s 
			inner join user u
				on u.id = s.user_id";
		
		$sql .= " where s.id = $id ";
		$this->attributes = $command->setText($sql)->queryRow();
	}
	
	public function loadItems()
	{
		$command = Yii::app()->db->createCommand();
		$this->items = $command->select('si.id, si.solution_id, si.item_id, i.description')
							->from('(solution_item si
													inner join item i
													on i.id = si.item_id)')
							->where('si.solution_id = ' . $this->id)
							->queryAll();
	}
					
	public function save()
	{
		
	}
	
	public function addItem($itemId)
	{
		$command = Yii::app()->db->createCommand();
		$command->insert('solution_item', 
					array(
						'solution_id' => $this->id,
						'item_id' => $itemId,
						));
		return true;
	}
	
	public function deleteItem($itemId)
	{
		$command = Yii::app()->db->createCommand();	
		
		$command->delete('solution_item', 
				'id = :id', array(':id' => $itemId)
				);
	}
}