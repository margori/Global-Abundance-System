<?php
class ServerModel extends CFormModel
{
	public $id;
	public $status;
	public $name;
	public $address;
	public $foreign_key;
	public $local_key;
	public $latitude;
	public $longitude;
	public $distance;
	
	public function rules()
	{
		return array(
			array('id', 'required'),
			array('status, name, address, foreign_key, local_key,
				latitude, longitude, distance', 'safe'),
		);
	}
		
	public function load($id)
	{
		$command = Yii::app()->db->createCommand();
		$sql = "select * from server s where id = $id ";
		$this->attributes = $command->setText($sql)->queryRow();
	}
	
	public function loadLocal()
	{
		$this->load(1);
	}
	
	public function browse($pageCurrent = 1, $pageSize = 10)
	{
		$command = Yii::app()->db->createCommand();
		
		$offset = ($pageCurrent - 1) * $pageSize;
		
		$sql = "select s.* ";
		$sql .= 'from `server` s ';
		$sql .= 'order by `distance` desc ';
		$sql .= "limit $pageSize offset $offset";
		
		$servers = $command->setText($sql)->queryAll();
		return $servers;
	}

	public function browseCount()
	{
		$command = Yii::app()->db->createCommand();
		
		$sql = "select count(*) ";
		$sql .= 'from `server` s ';
		
		$servers = $command->setText($sql)->queryScalar();
		return $servers;
	}

	public function save()
	{
		
	}
	
	public static function tableExists($table)
	{
		$command = Yii::app()->db->createCommand();		
		$sql = "SHOW TABLES LIKE '$table'";
		$tables = $command->setText($sql)->queryAll();
		return count($tables) > 0;
	}
	
	public function register($serverData)
	{
		return true;
	}
	
	public static function getAPI($url)
	{
		ini_set ( 'soap.wsdl_cache_enable' , 0 ); 
		ini_set ( 'soap.wsdl_cache_ttl' , 0 );
		
		$client = new SoapClient($url, array());
		return $client;
	}
}