<?php
include_once('protected/components/feed/EFeed.php');
include_once('protected/components/feed/EFeedTag.php');
include_once('protected/components/feed/EFeedItemAbstract.php');
include_once('protected/components/feed/EFeedItemRSS2.php');

class FeedController extends Controller
{
	public function actions()
	{
		return array(
		);
	}
	
	public function actionIndex()
	{
		$this->redirect(Yii::app()->createAbsoluteUrl(''));
	}
	
	public function actionNeeds()
	{
		$feeder = $this->NewFeeder();
		$feeder->setTitle($feeder->getTitle() . ' - Needs');
		
		$needModel = new ItemModel();
		$needs = $needModel->browse(null, 0, '', 1, 10);
		
		foreach($needs as $need)
		{
			$item = $feeder->createNewItem();
			
			$title = $need['user_name'] . ' needs ' . str_pad($need['description'], 50);
			$description = $need['user_name'] . ' needs ' . $need['description'];
			
			$item->setTitle(trim($title));
			$item->setDescription(trim($description));
			$item->setLink(Yii::app()->createAbsoluteUrl('need/' . $need['id']));
			$feeder->addItem($item);
		}
		$feeder->generateFeed();
		exit();
	}
	
	public function actionShares()
	{
		$feeder = $this->NewFeeder();
		$feeder->setTitle($feeder->getTitle() . ' - Shares');
		
		$sharesModel = new ItemModel();
		$shares = $sharesModel->browse(null, 1, '', 1, 10);
		
		foreach($shares as $share)
		{
			$item = $feeder->createNewItem();
			
			$title = $share['user_name'] . ' shares ' . str_pad($share['description'], 50);
			$description = $share['user_name'] . ' shares ' . $share['description'];
			
			$item->setTitle(trim($title));
			$item->setDescription(trim($description));
			$item->setLink(Yii::app()->createAbsoluteUrl('need/' . $share['id']));
			$feeder->addItem($item);
		}
		$feeder->generateFeed();
		exit();
	}
	
	private function NewFeeder()
	{
		$feeder = new EFeed();
		
		if (ConfigurationModel::instance()->app_title == '')
			$feeder->setTitle(Yii::t('global', 'title'));
		else
			$feeder->setTitle(ConfigurationModel::instance()->app_title);
		
		$feeder->setLink(Yii::app()->createAbsoluteUrl(''));		
		return $feeder;
	}
}