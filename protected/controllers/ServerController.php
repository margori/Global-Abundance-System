<?php
class ServerController extends Controller
{
	public function actionIndex()
	{
		$serverModel = new ServerModel();

		if (isset($_POST['filter']))
		{
			Yii::app()->user->setState('pageCurrent', 1);
		}

		if (isset($_GET['ps']))
		{
			Yii::app()->user->setState('pageSize', $_GET['ps']);
			Yii::app()->user->setState('pageCurrent', 1);
		}
		if (isset($_GET['p']))
			Yii::app()->user->setState('pageCurrent', $_GET['p']); 

		$pageSize = Yii::app()->user->getState('pageSize') ? Yii::app()->user->getState('pageSize') : 10;
		$pageCurrent = Yii::app()->user->getState('pageCurrent') ? Yii::app()->user->getState('pageCurrent') : 1;

		$rowCount = $serverModel->browseCount();  
		$pageCount = ceil($rowCount / $pageSize);
		if ($pageCurrent > $pageCount)
		{
			Yii::app()->user->setState('pageCurrent', 1);
			$pageCurrent = 1;
		}

		$servers = $serverModel->browse($pageCurrent, $pageSize);		
		$this->render('index',array(
				'servers' => $servers,
				'pageCurrent' => $pageCurrent,
				'pageSize' => $pageSize,
				'pageCount' => $pageCount,
		));
	}
	
	public function actionBackup()
	{
		if (Yii::app()->user->getState('user_id') != Yii::app()->params['root user id'])
			$this->redirect (Yii::app()->baseUrl);

    $return = '';

    $tables =array(
			'configuration',
			'server',
			'item',
			'item_comment',
			'solution',
			'solution_item',
			'solution_archive',
			'project',
			'unread_comment',
			'user',
			'user_heart',	
			'user_zone',
			);
  
		foreach($tables as $table)
		{
			$rows = Yii::app()->db->createCommand()->setText('SELECT * FROM '.$table)->queryAll();

			if (count($rows) > 0)
			{
				foreach($rows as $row)
				{
						$return.= 'INSERT INTO '.$table.' VALUES(';
						$fields = array();					
						foreach ($row as $field) 
						{
							$field = addslashes($field);
							$field = str_replace("\n","\\n",$field);
							if (isset($field)) 
								$fields[] = '\''.$field.'\'' ; 
							else 
								$fields[] .= '\'\'';
						}
						$fields = implode(', ', $fields);
						$return.= $fields . ");\n";
				}

				$return.= "\n\n";
			}
		}
  
		echo $return;
		exit();
	}
	
	public function actionInstall($data = null)
	{
		if ($data === 'new')
		{
			$_SESSION['installing'] = true;
			$_SESSION['install root'] = null;
		}
		
		if (!isset($_SESSION['install root']))
				$this->redirect($this->createUrl('server/root'));	
		else 
		{		
			$this->install_configuration();	
			$this->install_server();	
			$this->install_configuration();	
			$this->install_user_heart();	
			$this->install_user_zone();	
			$this->install_user();	
			$this->install_item();	
			$this->install_item_comment();	
			$this->install_solution();	
			$this->install_solution_item();	
			$this->install_solution_archive();	
			$this->install_unread_comment();	
			$this->install_project();	
		}

		$_SESSION['installing'] = false;
		$this->render('done');
	}
	
	public function actionRoot()
	{
		$message = "";
		if(isset($_POST['next']))
		{
			$currentUsername = Yii::app()->db->username;
			$currentPassword = Yii::app()->db->password;
			
			if (Yii::app()->db->active)
				Yii::app()->db->active = false;
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			Yii::app()->db->username = $username;
			Yii::app()->db->password = $password;
			
			try
			{
				Yii::app()->db->active = true;				
				$_SESSION['install username'] = $username;
				$_SESSION['install password'] = $password;
				$_SESSION['install root'] = true;
				$this->redirect($this->createUrl("server/install"));				
			}
			catch(Exception $ex)
			{
				Yii::app()->db->username = $currentUsername;
				Yii::app()->db->password = $currentPassword;
				$message = Yii::t('server','wrong credentials');
			}			
		}
		
		$this->render('root', array(
				'message' => $message,
		));		
	}

	public function install_configuration()
	{
		$fields = array(
			'app_title' => 'varchar(200) DEFAULT NULL',
			'default_language' => 'varchar(2) DEFAULT NULL',
			'development_url' => 'varchar(500) DEFAULT NULL',
			'blog_url' => 'varchar(500) DEFAULT NULL',
			'contact_email' => 'varchar(200) DEFAULT NULL',
			'host_name' => 'varchar(200) DEFAULT NULL',
			'host_url' => 'varchar(200) DEFAULT NULL',
			'send_emails' => 'int(1) DEFAULT NULL COMMENT \'1 = send emails when solution completed and taken\'',
			'smtp_server' => 'varchar(200) DEFAULT NULL',
			'stmp_port' => 'int(11) DEFAULT NULL',
			'smtp_username' => 'varchar(50) DEFAULT NULL',
			'smtp_password' => 'varchar(50) DEFAULT NULL',
			'smtp_from_email' => 'varchar(200) DEFAULT NULL',
			'smtp_from_name' => 'varchar(200) DEFAULT NULL',
			'smtp_secure' => "varchar(3) DEFAULT NULL COMMENT 'espace or ssl or tls'",
			'smtp_timeout' => 'int(11) DEFAULT NULL',
			'default_latitude' => 'double DEFAULT NULL',
			'default_longitude' => 'double DEFAULT NULL',
			'include_title_in_email' => 'bit(1) DEFAULT NULL',				
		);		
		
		$this->checkTable('configuration', $fields, null, null);		
		
		$command = Yii::app()->db->createCommand();
		$count = $command->setText("select count(*) from `configuration`")->queryScalar();
		if ($count < 1)
			$this->redirect($this->createUrl("server/configure"));	
	}

	
	public function actionConfigure()
	{
		$message = "";
		$configuration = new ConfigurationModel();
		
		if(isset($_POST['save']))
		{
			$configuration->attributes = $_POST;
			$message = $configuration->validateSave();
			if ($message == "")
				if($configuration->save())
				{
					if (!isset($_SESSION['installing']))
						$this->redirect($this->createUrl('./server'));				
					else if ($_SESSION['installing'])						
						$this->redirect($this->createUrl('server/install'));				
					else
						$this->redirect($this->createUrl('./server'));				
				}
		} 
		else if(isset($_POST['cancel'])) 
		{
			$this->redirect($this->createUrl('./server'));				
		}
		
		$configuration->load();
		
		$this->render("configure", array(
				'configuration' => $configuration,
				'message' => $message,
				'languages'=>Yii::app()->params['languages'],
		));
	}
	
	public function install_server()
	{
		$fields = array(
			'id' => 'int(11) NOT NULL AUTO_INCREMENT',
			'status' => 'int(11) DEFAULT \'1\' COMMENT \'0 - offline, 1 - new, 2 - online\'',
			'name' => 'varchar(1000)',
			'address' => 'varchar(1000) COMMENT \'ip or url\'',
			'foreign_key' => 'varchar(64) COMMENT \'md5 key set by foreign server\'',
			'local_key' => 'varchar(64) COMMENT \'md5 key set by local server\'',
			'latitude' => 'double',
			'longitude' => 'double',
			'distance' => 'double COMMENT \'distance from local server\'',
		);		
		
		$_SESSION['install server'] = $this->checkTable('server', $fields, 'id', null);
		
		if ($_SESSION['install server'])
		{
			$command = Yii::app()->db->createCommand();
			$count = $command->setText("select count(*) from `server`")->queryScalar();
			if ($count < 1)
			{
				$now = date('r') . rand(10000, 99999);
				$key = md5($now);
				
				$command = Yii::app()->db->createCommand();
				$command->insert('server', array(
					'id' => '1',
					'status' => '2',
					'name' => ConfigurationModel::instance()->app_title,
					'address' => $_SERVER['SERVER_NAME'] . Yii::app()->baseUrl,
					'foreign_key' => $key,
					'local_key' => $key,
					'latitude' => ConfigurationModel::instance()->default_latitude,
					'longitude' => ConfigurationModel::instance()->default_longitude,
					'distance' => '0',
				));				
			}
		}
	}
	
	public function install_user()
	{
		$fields = array(
				'id' => 'int(11) NOT NULL AUTO_INCREMENT',
				'username' => 'varchar(250)',
				'password' => 'varchar(50)',
				'password_salt' => 'varchar(32) DEFAULT \'This is a default salt string\'',
				'real_name' => 'varchar(200) DEFAULT NULL',
				'email' => 'varchar(200) DEFAULT NULL',
				'language' => 'varchar(2) DEFAULT \'en\'',
				'last_login' => 'date DEFAULT NULL',
				'about' => 'text DEFAULT NULL',
		);		
		
		$result = $this->checkTable('user', $fields, 'id', null);
		
		$count = Yii::app()->db->createCommand()
						->select('count(*)')
						->from('user')
						->queryScalar();
		
		if ($count < 1)
		{
			$registerModel = new RegisterModel();
			$registerModel->username = 'admin';
			$registerModel->password = 'admin';
			$registerModel->language = ConfigurationModel::instance()->default_language;
			$registerModel->email = ConfigurationModel::instance()->contact_email;
			$registerModel->register();
		}
		
		return $result;
	}
	
	public function install_user_heart()
	{
		$fields = array(
			'from_user_id' => 'int(11)',
			'to_user_id' => 'int(11)',
			'love' => 'int(11)',
		);		
		
		return $this->checkTable('user_heart', $fields, 'from_user_id`, `to_user_id', array('from_user_id', 'to_user_id'));		
	}

	public function install_user_zone()
	{
		$fields = array(
				'id' => 'int(11) NOT NULL AUTO_INCREMENT',
				'user_id' => 'int(11)',
				'top' => 'double COMMENT \'latitude\'',
				'right' => 'double COMMENT \'longitude\'',
				'bottom' => 'double COMMENT \'latitude\'',
				'left' => 'double COMMENT \'longitude\'',
		);		
		
		return $this->checkTable('user_zone', $fields, 'id', array('user_id'));		
	}

	public function install_item()
	{
		$fields = array(
			'id' => 'int(11) NOT NULL AUTO_INCREMENT',
			'description' => 'text COMMENT \'includes tags prefixed with #\'',
			'shared' => 'tinyint(1) DEFAULT \'0\' COMMENT \'false means this item is need for an user, true means served.\'',
			'user_id' => 'int(11)',
			'quantity' => 'int(11) COMMENT \'< 0 is infinite quantity\'',
			'original_description' => 'text',
			'expiration_date' => 'date',
			'creation_date' => 'date',
			'notified' => 'tinyint(1) DEFAULT \'0\'',
			'project_id' => 'int(11) DEFAULT NULL',
		);		
		
		return $this->checkTable('item', $fields, 'id', array('user_id', 'project_id'));		
	}

	public function install_item_comment()
	{
		$fields = array(
			'id' => 'int(11) NOT NULL AUTO_INCREMENT',
			'comment' => 'text',
			'item_id' => 'int(11)',
			'user_id' => 'int(11)',
		);		
		
		return $this->checkTable('item_comment', $fields, 'id', array('item_id', 'user_id'));		
	}

	public function install_solution()
	{
		$fields = array(
			'id' => 'int(11) NOT NULL AUTO_INCREMENT',
			'user_id' => 'int(11)',
			'status' => 'int(11) DEFAULT \'1\' COMMENT \'1-draft,2-completed\'',
			'item_id' => 'int(11)',
			'read' => 'int(1) DEFAULT \'0\'',
		);		
		
		return $this->checkTable('solution', $fields, 'id', array('item_id', 'user_id'));
	}

	public function install_solution_archive()
	{
		$fields = array(
			'id' => 'int(11) NOT NULL AUTO_INCREMENT',
			'description' => 'text',
		);		
		
		return $this->checkTable('solution_archive', $fields, 'id', null);
	}

	public function install_solution_item()
	{
		$fields = array(
			'id' => 'int(11) NOT NULL AUTO_INCREMENT',
			'solution_id' => 'int(11)',
			'item_id' => 'int(11)',
		);		
		
		return $this->checkTable('solution_item', $fields, 'id', array('item_id', 'solution_id'));
	}

	public function install_unread_comment()
	{
		$fields = array(
			'comment_id' => 'int(11)',
			'user_id' => 'int(11)',
		);		
		
		return $this->checkTable('unread_comment', $fields, 'comment_id`, `user_id', null);
	}

	public function install_project()
	{
		$fields = array(
			'id' => 'int(11) NOT NULL AUTO_INCREMENT',
			'name' => 'varchar(200)',
			'description' => 'text',
			'user_id' => 'int(11)',
		);		
		
		return $this->checkTable('project', $fields, 'id', array('user_id'));
	}

	// AUXILIAR FUNCTIONS -------------------------
	
	public function checkTable($table, $fields, $primaryKey, $indexes)
	{
		$currentUsername = Yii::app()->db->username;
		$currentPassword = Yii::app()->db->password;

		Yii::app()->db->username = $_SESSION['install username'];
		Yii::app()->db->password = $_SESSION['install password'];
		
		if (!ServerModel::tableExists($table))
			$this->createTable($table, $fields, $primaryKey, $indexes);
		
		$columns = Yii::app()->db->createCommand()
						->setText("show columns from `$table`")
						->queryAll();
				
		foreach ($fields as $field_name => $field_definition)
		{
			$exists = false;

			foreach($columns as $column)
			{
				if ($column['Field'] == $field_name)
				{
					$exists = true;
					$index = strpos($field_definition, ' ');
					
					if (!isset($index) || !$index)
						$expected_type = $field_definition;
					else
						$expected_type = substr($field_definition, 0, $index);
					if($column['Type'] != $expected_type)
					{
						Yii::app()->db->createCommand()
										->setText("ALTER TABLE $table MODIFY $field_name $field_definition")
										->execute();
					}
					break;
				}
			}
			
			if (!$exists)
			{
				Yii::app()->db->createCommand()
								->setText("ALTER TABLE $table ADD $field_name $field_definition")
								->execute();
			}
		}
		
		Yii::app()->db->username = $currentUsername;
		Yii::app()->db->password = $currentPassword;
		return true;
	}
	
	public function createTable($table, $fields, $primaryKey, $indexes)
	{
		if ($primaryKey == null)
			$primaryKey = '';
		if ($indexes == null)
			$indexes = array();
		
		$header = "create table `$table` (";
		
		foreach ($fields as $field_name => $field_definition)
			$fieldsArray[] = "`$field_name` $field_definition";		
		if ($primaryKey != '')
			$fieldsArray[] = "PRIMARY KEY (`$primaryKey`)";
		foreach($indexes as $index)
			$fieldsArray[] = "KEY `$index` (`$index`)";
		
		$fields = implode(', ', $fieldsArray);
		$footer = ') ENGINE=MyISAM  DEFAULT CHARSET=utf8 ';
		Yii::app()->db->createCommand()
								->setText($header . $fields . $footer)
								->execute();	
	}
	
	public function actionUpdate()
	{
		$serverModel = new ServerModel();
		$servers = $serverModel->browse(1,1000);
		
		foreach($servers as $server)
		{
			$url = 'http://' . $server['address'] . '/index.php/API/index';
			$api = ServerModel::getAPI($url);
			$result = $api->GetServers();

			if ($result['status'] == 'ok')
			{
				$servers = $result['servers'];
				foreach ($servers as $server)
					echo $server['name'];
			}
				
		}
		$this->render('update');
	}

	public function actionRegister()
	{
		$message = "";
	
		if(isset($_POST['save']))
		{
			$url = $_POST['url'];
			if (trim($url) == '')
				$this->redirect($this->createUrl('./server'));
			
			$index = strpos($url, 'index.php');
			if ($index > 0)
			{
				$url = substr($url, 0, $index - 1);
			}
			
			$api = $this->getAPI($url);
			
			$serverModel = new ServerModel();			
			$serverModel->loadLocal();

			$now = date('r') . rand(10000, 99999);
			$newLocalKey = md5($now);
 
			$localData = array(
				'address' => $serverModel->address,
				'name' => $serverModel->name,
				'foreignKey' => $newLocalKey,
				'latitude' => $serverModel->latitude,
				'longitude' => $serverModel->longitude,
				);
			
			$serverData = $api->getData($localData);
			$serverModel->register($serverData);
			$this->redirect($this->createUrl('./server'));				
		} 
		else if(isset($_POST['cancel'])) 
		{
			$this->redirect($this->createUrl('./server'));				
		}
		
		$this->render("register", array(
				'message' => $message,
		));		
	}
}