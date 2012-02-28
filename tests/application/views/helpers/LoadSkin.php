<?php
class Zend_View_Helper_LoadSkin extends Zend_View_Helper_Abstract
{
	public function loadSkin($skin)
	{
		$skinData = new Zend_Config_Xml('./skins/'.$skin.'/skin.xml');
		$stylesheets = $skinData->stylesheets->stylesheet->toArray();
		if (is_array($stylesheets)) {
			foreach ($stylesheets as $stylesheet) {
				$this->view->headLink()->appendStylesheet('/skins/'.$skin.'/css/'.$stylesheet);
			}
		}
	}
}