<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
	function __construct($id,$module=null) {
			parent::__construct($id,$module=null);

			// Setting history
			$previousUrl = Yii::app()->user->getState('current url');
			Yii::app()->user->setState('previous url', $previousUrl);
			$currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			Yii::app()->user->setState('current url', $currentUrl);

			// Setting language
			$currentLanguage = Yii::app()->user->getState('language');
			if (!$currentLanguage || $currentLanguage == '')
			{
				$currentLanguage = ConfigurationModel::instance()->default_language;
				Yii::app()->user->getState('language', $currentLanguage);
			}
			Yii::app()->language = $currentLanguage;

			if (Yii::app()->user->isGuest && $_SERVER['REQUEST_URI'] == $this->createUrl('.'))
				$this->redirect(Yii::app()->homeUrl);			
	}
}