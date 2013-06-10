<?php
class ConfigurationModel extends CFormModel
{
	public $app_title = 'Global Abundance System';
	public $default_language = 'en';
	public $development_url;
	public $blog_url;
	public $contact_email;
	public $host_name;
	public $host_url;
	public $send_emails = 0;
	public $smtp_server;
	public $stmp_port;
	public $smtp_username;
	public $smtp_password;
	public $smtp_from_email;
	public $smtp_from_name;
	public $smtp_secure;
	public $smtp_timeout;
	public $default_latitude = -32.88949;
	public $default_longitude = -68.84456;
	public $include_title_in_email = 1;		
	
	public static function instance()
	{
		if (!isset($_SESSION['configuration']))
		{
			$_instance = new ConfigurationModel ();
			$_instance->load();
			$_SESSION['configuration'] = $_instance; 
		}
		return $_SESSION['configuration'];		
	}
	
	public function rules()
	{
		return array(
			array('', 'required'),
			array('app_title, default_language, development_url, blog_url,
				contact_email, host_name, host_url, send_emails, smtp_server,
				stmp_port, smtp_username, smtp_password, smtp_from_email,
				smtp_from_name, smtp_secure, smtp_timeout, default_latitude,
				default_longitude, include_title_in_email', 'safe'),
		);
	}
		
	public function load()
	{
		$command = Yii::app()->db->createCommand();
		
		if (ServerModel::tableExists('configuration'))
		{		
			$sql = "select * from configuration s ";
			$this->attributes = $command->setText($sql)->queryRow();
		}
		else
		{
			$uri = $_SERVER["REQUEST_URI"];
			$installing = strpos($uri, 'server');
			if (!$installing)
			{
				$redirect = Yii::app()->createUrl('server/install/new');
				Yii::app()->getRequest()->redirect($redirect);
			}
		}	
	}
	
	public function validateSave()
	{
		return "";
	}

	public function save()
	{
		// Clean previous configuration
		Yii::app()->db->createCommand()
						->setText("delete from configuration")->execute();
		
		Yii::app()->db->createCommand()->insert("configuration", array(
				'app_title' => $this->app_title,
				'default_language' => $this->default_language,
				'development_url' => $this->development_url,
				'blog_url' => $this->blog_url,
				'contact_email' => $this->contact_email,
				'host_name' => $this->host_name,
				'host_url' => $this->host_url,
				'send_emails' => $this->send_emails,
				'smtp_server' => $this->smtp_server,				
				'stmp_port' => $this->stmp_port,
				'smtp_username' => $this->smtp_username,
				'smtp_password' => $this->smtp_password,
				'smtp_from_email' => $this->smtp_from_email,
				'smtp_from_name' => $this->smtp_from_name,
				'smtp_secure' => $this->smtp_secure,
				'smtp_timeout' => $this->smtp_timeout,
				'default_latitude' => $this->default_latitude,
				'default_longitude' => $this->default_longitude,
				'include_title_in_email' => $this->include_title_in_email === '' ? '1' : $this->include_title_in_email ,
		));
		return true;
	}
}