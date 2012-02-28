<?php

class PageController extends Zend_Controller_Action
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
        $pageForm = new Form_PageForm();
        $pageForm->setAction('/page/create');
        $this->view->form = $pageForm;
    }


}



