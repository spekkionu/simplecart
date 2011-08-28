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

    $manager = Doctrine_Manager::getInstance();
    $manager->registerHydrator('jstree', 'Hydrator_JsTree');

    $table = Doctrine::getTable('Category');
    $q = Doctrine_Query::create();
    $q->select('id,name');
    $q->from('Category');
    $q->setHydrationMode('jstree');
    $treeObject = $table->getTree();
    $treeObject->setBaseQuery($q);
    $categories = $treeObject->fetchTree();
    $this->view->categories = $categories;

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
    if(!$this->getRequest()->isXmlHttpRequest()){
      return $this->_forward('not-found', 'error', 'default');
    }
    if(!$this->getRequest()->isPost()){
      return $this->sendJson(array('success'=>false,'message'=>'This must be a POST request.'));
    }
    $id = $this->getRequest()->getPost('category');
    $position = $this->getRequest()->getPost('position');
    $ref = $this->getRequest()->getPost('ref');
    try{
      Doctrine::getTable('Category')->moveCategory($id, $ref, $position);
      return $this->sendJson(array('success'=>true,'message'=>'Successfully moved category.'));
    }catch(Exception $e){
      return $this->sendJson(array('success'=>false,'message'=>'Failed to move category.'));
    }
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