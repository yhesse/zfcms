<?php

class PageController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $pageModel = new Model_Page();
        $recentPages = $pageModel->getRecentPages();
        
        if (is_array($recentPages)) {
        	for ($i = 1; $i <= 3; $i++) {
        		if (count($recentPages) > 0) {
        			$featuredItems[] = array_shift($recentPages);
        		}
        	}
        	$this->view->featuredItems = $featuredItems;
        }
        if (count($recentPages) > 0) {
        	$this->view->recentPages = $recentPages;
        } else {
        	$this->view->recentPages = null;
        }
        
    }

    public function createAction()
    {
        $pageForm = new Form_PageForm();
        if ($this->getRequest()->isPost()) {
        	if ($pageForm->isValid($_POST)) {
	        	$itemPage = new CMS_Content_Item_Page();
	        	$itemPage->name = $pageForm->getValue('name');
	        	$itemPage->headline = $pageForm->getValue('headline');
	        	$itemPage->description = $pageForm->getValue('description');
	        	$itemPage->content = $pageForm->getValue('content');
	
	        	if ($pageForm->image->isUploaded()) {
	        		$pageForm->image->receive();
	        		$itemPage->image = '/images/upload/'.basename($pageForm->image->getFileName());
	        	}
	        	
	        	$itemPage->save();
	        	return $this->_forward('list');
        	}
        }
        $pageForm->setAction('/page/create');
        $this->view->form = $pageForm;
    }

    public function listAction()
    {
        $pageModel = new Model_Page();
        $select = $pageModel->select();
        $select->order('name');
        $currentPages = $pageModel->fetchAll($select);
        if ($currentPages->count() > 0) {
        	$this->view->pages = $currentPages;
        } else {
        	$this->view->pages = null;
        }
        
    }

    public function editAction()
    {
        $id = $this->_request->getParam('id');
        $itemPage = new CMS_Content_Item_Page($id);
        $pageForm = new Form_PageForm();
        $pageForm->setAction('page/edit');
        $pageForm->populate($itemPage->toArray());
        $imagePreview = $pageForm->createElement('image', 'image_preview');
        $imagePreview->setLabel('Preview Image: ');
        $imagePreview->setAttrib('style', 'width: 200px; height: auto;');
        $imagePreview->setOrder(4);
        $imagePreview->setImage($itemPage->image);
        $pageForm->addElement($imagePreview);
        $this->view->form = $pageForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');
        $itemPage = new CMS_Content_Item_Page($id);
        $itemPage->delete();
        return $this->_forward('list');
    }

    public function openAction()
    {
        $id = $this->_request->getParam('id');
        $title = $this->_request->getParam('title');
        $pageModel = new Model_Page();
        $select = $pageModel->select();
        $select->where('name = ?', $title);
        $row = $pageModel->fetchRow($select);
        if ($row) {
        	$this->view->page = new CMS_Content_Item_Page($row->id);
        } else {
        	throw new Zend_Controller_Action_Exception(
        			"The page you requested was not found", 404);
        }
    }


}











