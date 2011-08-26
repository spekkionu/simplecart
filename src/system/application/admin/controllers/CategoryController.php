<?php

class Admin_CategoryController extends SimpleCart_AdminController {

  public function init(){
    parent::init();
    if(!$this->isAllowed('admin', 'general')){
      if(Zend_Auth::getInstance()->hasIdentity()){
        return $this->_forward('denied', 'access', 'admin');
      }else{
        // Save current url for later
        $session = new Zend_Session_Namespace();
        $session->login_destination = $this->view->url();
        return $this->routeRedirect('admin_login');
      }
    }
  }

  public function indexAction(){

  }

  public function loadDataAction(){
    if(!$this->getRequest()->isXmlHttpRequest()){
      return $this->_forward('not-found', 'error', 'default');
    }
    $categories = Doctrine::getTable('Category')->getCategoryTree();
    return $this->sendJson(array(
      'identifier' => 'id',
      'label' => 'name',
      'items' => $categories
    ));
  }

  public function moveAction(){

  }

  public function addAction(){

  }

  public function detailsAction(){

  }

  public function editAction(){

  }

  public function deleteAction(){

  }
}