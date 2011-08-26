<?php

class Admin_AccountController extends SimpleCart_AdminController {

  public function init(){
    parent::init();
    if(!$this->isAllowed('admin', 'general')){
      // Save current url for later
      $session = new Zend_Session_Namespace();
      $session->login_destination = $this->view->url();
      return $this->routeRedirect('admin_login');
    }
  }

  public function indexAction(){
    $profile = Doctrine::getTable('Admin')->getProfile($this->identity->id);
    if(!$profile){
      return $this->redirect('not-found', 'error');
    }
    $this->view->profile = $profile;
  }

  public function editAction(){
    $form = new Form_AdminProfile();
    $profile = Doctrine::getTable('Admin')->getProfile($this->identity->id);
    $form->populate($profile);
    $form->addDbValidators($this->identity->id);
    if($this->getRequest()->isPost()){
      if($form->isValid($this->getRequest()->getPost())){
        try{
          $values = $form->getValues();
          Doctrine::getTable('Admin')->updateProfile($this->identity->id, $values);
          $this->addMessage("Successfully updated profile.", 'success');
          return $this->redirect('index');
        }catch(Exception $e){
          $this->addMessage("Failed to update profile.", 'error');
          return $this->redirect('edit');
        }
      }
    }
    $this->view->form = $form;
  }

  public function passwordAction(){
    $form = new Form_ChangePassword();
    $form->removeElement('pin');
    if($this->getRequest()->isPost()){
      if($form->isValid($this->getRequest()->getPost())){
        try{
          try{
            Doctrine::getTable('Admin')->changePassword($this->identity->id, $form);
            $this->addMessage("Successfully changed account password.", 'success');
            return $this->redirect('index');
          }catch(Validate_Exception $e){
            // Failed validation, do nothing, redisplay form
          }
        }catch(Exception $e){
          $this->addMessage("Failed to change password.", 'error');
          return $this->redirect('password');
        }
      }
    }
    $this->view->form = $form;
  }
}