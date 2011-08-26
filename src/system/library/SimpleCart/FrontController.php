<?php

abstract class SimpleCart_FrontController extends SimpleCart_Controller {

  /**
   * Controller init
   */
  public function init(){
    parent::init();
  }

  /**
   * Controller post dispatch
   */
  public function postDispatch(){
    // Check for theme layout
    if(file_exists($this->config->system . '/theme/layout/'.$this->_helper->layout->getLayout().'.phtml')){
      $this->_helper->layout->setLayoutPath($this->config->system . '/theme/layout');
    }
    // Add theme view script path
    $this->view->addBasePath($this->config->system . '/theme');
  }

}