<?php

class BugController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function createAction()
    {
        // action body
    }

    public function submitAction()
    {
        $bugReportForm = new Form_BugReportForm();
        //$bugReportForm->setAction('/bug/submit');
        $bugReportForm->setMethod('post');
        $bugReportForm->date->setValue(date('d-m-Y'));
        if ($this->getRequest()->isPost()) {
            if ($bugReportForm->isValid($_POST)) {
                $bugModel = new Model_Bug();
                $result = $bugModel->createBug(
                		$bugReportForm->getValue('author'), 
                		$bugReportForm->getValue('email'), 
                		$bugReportForm->getValue('date'), 
                		$bugReportForm->getValue('url'), 
                		$bugReportForm->getValue('description'), 
                		$bugReportForm->getValue('priority'), 
                		$bugReportForm->getValue('status'));
                if ($result) {
                	$this->_helper->redirector('confirm');
                }
            }
        }
        $this->view->form = $bugReportForm;
    }

    public function confirmAction()
    {
        // action body
    }

    public function listAction()
    {
    	$listToolsForm = new Form_BugReportListToolsForm();
    	$listToolsForm->setAction('/bug/list');
    	$listToolsForm->setMethod('post');
    	$this->view->listToolsForm = $listToolsForm;
    	
    	$sort = $this->_request->getParam('sort', null);
    	$filterField = $this->_request->getParam('filter_field', null);
    	$filterValue = $this->_request->getParam('filter');
    	
    	if (!empty($filterField)) {
    		$filter[$filterField] = $filterValue;
    	} else {
    		$filter = null;
    	}
    	
    	$listToolsForm->getElement('sort')->setValue($sort);
    	$listToolsForm->getElement('filter_field')->setValue($filterField);
    	$listToolsForm->getElement('filter')->setValue($filterValue);
    	
        $bugModel = new Model_Bug();
        $adapter = $bugModel->fetchPaginatorAdapter($filter, $sort);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(10);
        $page = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($page);
        
        $this->view->paginator = $paginator;
    }

    public function editAction()
    {
        $bugModel = new Model_Bug();
        $bugReportForm = new Form_BugReportForm();
        $bugReportForm->setAction('/bug/edit');
        $bugReportForm->setMethod('post');
        
        if ($this->getRequest()->isPost()) {
        	if ($bugReportForm->isValid($_POST)) {
        		$result = $bugModel->updateBug(
        				$bugReportForm->getValue('id'), 
        				$bugReportForm->getValue('author'), 
        				$bugReportForm->getValue('email'), 
        				$bugReportForm->getValue('date'), 
        				$bugReportForm->getValue('url'), 
        				$bugReportForm->getValue('description'), 
        				$bugReportForm->getValue('priority'), 
        				$bugReportForm->getValue('status'));
        		$this->_helper->redirector('list');
        	}
        } else {
        	$id = $this->_request->getParam('id');
        	$bug = $bugModel->find($id)->current();
        	$bugReportForm->populate($bug->toArray());
        	$bugReportForm->getElement('date')->setValue(date('d-m-Y', $bug->date));
        	$this->view->bug = $bug;
        }
        
        $this->view->form = $bugReportForm;
    }

    public function deleteAction()
    {
        $bugModel = new Model_Bug();
        $id = $this->_request->getParam('id');
        $bugModel->deleteBug($id);
        $this->_helper->redirector('list');
    }


}













