<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initView()
	{
		$view = new Zend_View();
		$view->doctype('XHTML1_STRICT');
		$view->headTitle('Zend cms');
		$view->skin = 'blues';
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);
		return $view;
	}
	
	protected function _initMenus ()
	{
		$view = $this->getResource('view');
		$view->mainMenuId = 1;
		$view->adminMenuId = 2;
	}
	
	protected function _initAutoload()
	{
		$autoLoader = Zend_Loader_Autoloader::getInstance();
		$autoLoader->registerNamespace('CMS_');
		$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
				'basePath' => APPLICATION_PATH , 
				'namespace' => '' , 
				'resourceTypes' => array(
						'form' => array(
								'path' => 'forms/' , 
								'namespace' => 'Form_'
								) , 
						'model' => array(
								'path' => 'models/' , 
								'namespace' => 'Model_'
								)
						)
				));
		return $autoLoader;
	}

}

