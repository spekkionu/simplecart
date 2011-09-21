<?php

/**
 * Product management
 *
 * @package Simplecart
 * @subpackage AdminController
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Admin_ProductController extends SimpleCart_AdminController
{

  public function init() {
    parent::init();
    if (!$this->isAllowed('admin', 'general')) {
      // Save current url for later
      $session = new Zend_Session_Namespace();
      $session->login_destination = $this->view->url();
      return $this->routeRedirect('admin_login');
    }
  }

  public function indexAction() {
    if (!$this->isAllowed('admin:product', 'view')) {
      return $this->_forward('denied', 'access', 'admin');
    }
    $rowsPerPage = 50;
    $page = $this->getRequest()->getParam('page');
    $sort = $this->getRequest()->getParam('sort');
    $dir = $this->getRequest()->getParam('dir');
    $form = new Form_Search_Product();
    if ($this->getRequest()->isPost()) {
      if ($this->getRequest()->getPost('clear')) {
        return $this->routeRedirect('admin_product', array(), true);
      }
      if ($form->isValid($this->getRequest()->getPost())) {
        $values = $form->getValues();
        return $this->routeRedirect('admin_product', array(
            'page' => 1,
            'sort' => $sort,
            'dir' => $dir,
            'productid' => $values['productid'],
            'name' => $values['name'],
            'sku' => $values['sku'],
            'active' => $values['active'],
            'category' => $values['category']
            ), true);
      }
    }
    $results = Doctrine::getTable('Product')->getAllProducts($page, $rowsPerPage, $sort, $dir);
    $this->view->form = $form;
    $this->view->results = $results;
    $this->view->sort = $sort;
    $this->view->dir = $dir;
    // Pass Categories
    $categories = Doctrine::getTable('Category')->getCategoryTree();
    $this->view->categories = $categories;
  }

  public function sortAction() {
    if (!$this->isAllowed('admin:product', 'edit')) {
      return $this->_forward('denied', 'access', 'admin');
    }
  }

  public function addAction() {
    if (!$this->isAllowed('admin:product', 'add')) {
      return $this->_forward('denied', 'access', 'admin');
    }
  }

  public function editAction() {
    if (!$this->isAllowed('admin:product', 'edit')) {
      return $this->_forward('denied', 'access', 'admin');
    }
  }

  public function deleteAction() {
    if (!$this->isAllowed('admin:product', 'delete')) {
      return $this->_forward('denied', 'access', 'admin');
    }
  }

}