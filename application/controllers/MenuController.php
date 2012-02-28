<?php

class MenuController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $mdlMenu = new Model_Menu();
        $this->view->menus = $mdlMenu->getMenus();
    }

    public function createAction()
    {
        $frmMenu = new Form_Menu();
        
        if ($this->getRequest()->isPost()) {
        	if ($frmMenu->isValid($_POST)) {
        		$menuName = $frmMenu->getValue('name');
        		$mdlMenu = new Model_Menu();
        		$result = $mdlMenu->createMenu($menuName);
        		if ($result) {
        			$this->_redirect('/menu/index');
        		}
        	}
        }
        
        $frmMenu->setAction('/menu/create');
        $this->view->form = $frmMenu;
        
    }

    public function editAction()
    {
        $id = $this->_request->getParam('id');
        $mdlMenu = new Model_Menu();
        $frmMenu = new Form_Menu();
        if ($this->getRequest()->isPost()) {
        	if($frmMenu->isValid($_POST)) {
        		$menuName = $frmMenu->getValue('name');
        		$result = $mdlMenu->updateMenu($id, $menuName);
        		if ($result) {
        			return $this->_forward('index');
        		}
        	}
        } else {
        	$currentMenu = $mdlMenu->find($id)->current();
        	$frmMenu->getElement('id')->setValue($currentMenu->id);
        	$frmMenu->getElement('name')->setValue($currentMenu->name);
        }
        $frmMenu->setAction('/menu/edit');
        $this->view->form = $frmMenu;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');
        $mdlMenu = new Model_Menu();
        $mdlMenu->deleteMenu($id);
        $this->_forward('index');
    }

    public function renderAction()
    {
    	$menu = $this->_request->getParam('menu');
        $mdlMenuItems = new Model_MenuItem();
        $menuItems = $mdlMenuItems->getItemsByMenu($menu);
        if (count($menuItems) > 0) {
        	foreach($menuItems as $item) {
        		$label = $item->label;
        		if (!empty($item->link)) {
        			$uri = $item->link;
        		} else {
        			$page = new CMS_Content_Item_Page($item->page_id);
        			$uri = '/page/open/title/'.$page->name;
        		}
        		$itemArray[] = array(
        				'label' => $label,
        				'uri' => $uri
        				);
        		$container = new Zend_Navigation($itemArray);
        		$this->view->navigation()->setContainer($container);
        	}
        }
    }


}









