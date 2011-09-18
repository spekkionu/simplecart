<?php

/**
 * Main index controller for front end
 *
 * @package Simplecart
 * @subpackage Controller
 * @author spekkionu
 */
class IndexController extends SimpleCart_FrontController
{

  public function init() {
    parent::init();
  }

  public function indexAction() {

  }

  public function pageAction() {
    $page = $this->getRequest()->getParam('page');
  }

  public function contactAction() {

  }

}