<?php

/**
 * Catagory management
 *
 * @package Simplecart
 * @subpackage AdminController
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Admin_CategoryController extends SimpleCart_AdminController
{

  public function init() {
    parent::init();
    if (!$this->isAllowed('admin', 'general')) {
      if (Zend_Auth::getInstance()->hasIdentity()) {
        return $this->_forward('denied', 'access', 'admin');
      } else {
        // Save current url for later
        $session = new Zend_Session_Namespace();
        $session->login_destination = $this->view->url();
        return $this->routeRedirect('admin_login');
      }
    }
  }

  public function indexAction() {
    if (!$this->isAllowed('admin:category', 'view')) {
      return $this->_forward('denied', 'access', 'admin');
    }
    $categories = Doctrine::getTable('Category')->getCategoryTree();
    $this->view->categories = $categories;
  }

  public function detailsAction() {
    if ($this->getRequest()->isXmlHttpRequest()) {
      // Disable Layout for AJAX Requests
      $this->_helper->layout->disableLayout();
    }
    if (!$this->isAllowed('admin:category', 'view')) {
      return $this->_forward('denied', 'access', 'admin');
    }
    $id = intval($this->getRequest()->getParam('id'));
    if (!$id) {
      return $this->_forward('not-found', 'error', 'default');
    }
    $category = Doctrine::getTable('Category')->getCategory($id);
    if (!$category) {
      return $this->_forward('not-found', 'error', 'default');
    }
    $this->view->category = $category;
  }

  public function moveAction() {
    if (!$this->getRequest()->isXmlHttpRequest()) {
      return $this->_forward('not-found', 'error', 'default');
    }
    if (!$this->isAllowed('admin:category', 'edit')) {
      return $this->_forward('denied', 'access', 'admin');
    }
    if (!$this->getRequest()->isPost()) {
      return $this->sendJson(array('success' => false, 'message' => 'This must be a POST request.'));
    }
    $id = $this->getRequest()->getPost('category');
    $position = $this->getRequest()->getPost('position');
    $ref = $this->getRequest()->getPost('ref');
    try {
      Doctrine::getTable('Category')->moveCategory($id, $ref, $position);
      return $this->sendJson(array('success' => true, 'message' => 'Successfully moved category.'));
    } catch (Exception $e) {
      return $this->sendJson(array('success' => false, 'message' => 'Failed to move category.'));
    }
  }

  public function addAction() {
    if (!$this->isAllowed('admin:category', 'add')) {
      return $this->_forward('denied', 'access', 'admin');
    }
    $id = $this->getRequest()->getParam('parent');
    $parent = Doctrine::getTable('Category')->getCategory($id);
    if (!$parent) {
      $id = NULL;
    }
    $form = new Form_Category();
    $form->addDbValidators();
    if ($id) {
      $form->getElement('parent')->setValue($id);
    }
    if ($this->getRequest()->isPost()) {
      if ($form->isValid($this->getRequest()->getPost())) {
        try {
          $values = $form->getValues();
          Doctrine::getTable('Category')->addCategory($values);
          $this->addMessage("Category {$values['name']} added.", 'success');
          return $this->routeRedirect("admin_category");
        } catch (Exception $e) {
          $this->logError("Error adding new category. - {$e->getMessage()}");
          $this->addMessage("Error adding category", 'error');
          return $this->routeRedirect('admin_category_add', array('parent' => $values['parent']));
        }
      }
      // Refresh parent category
      $id = $form->getElement('parent')->getValue();
    }
    $this->view->form = $form;
    // Pass the parent category
    $this->view->parent = $id;
    // Pass Categories
    $categories = Doctrine::getTable('Category')->getCategoryTree();
    $this->view->categories = $categories;

    // Activate Menu Items
    $container = Zend_Registry::get('Zend_Navigation');
    $found = $container->findOneByRoute('admin_category_add');
    $found->set('params', array('parent' => $id));
  }

  public function editAction() {
    if (!$this->isAllowed('admin:category', 'edit')) {
      return $this->_forward('denied', 'access', 'admin');
    }
    $id = $this->getRequest()->getParam('id');
    $category = Doctrine::getTable('Category')->find($id);
    if (!$id) {
      return $this->_forward('not-found', 'error', 'default');
    }
    $form = new Form_Category();
    $form->removeElement('parent');
    $form->addDbValidators($category->route_id);
    $form->populate($category->toArray());
    if ($this->getRequest()->isPost()) {
      if ($form->isValid($this->getRequest()->getPost())) {
        try {
          $values = $form->getValues();
          Doctrine::getTable('Category')->updateCategory($category, $values);
          $this->addMessage("Category {$values['name']} updated.", 'success');
          return $this->routeRedirect("admin_category");
        } catch (Exception $e) {
          $this->logError("Error updating category. - {$e->getMessage()}");
          $this->addMessage("Error updating category", 'error');
          return $this->routeRedirect('admin_category_edit', array('id' => $id));
        }
      }
    }

    $this->view->form = $form;
    $this->view->category = $category;
    // Activate Menu Items
    $container = Zend_Registry::get('Zend_Navigation');
    $found = $container->findOneByRoute('admin_category_edit');
    $found->setVisible(true);
    $found->set('params', array('id' => $id));
  }

  public function deleteAction() {
    if (!$this->isAllowed('admin:category', 'delete')) {
      return $this->_forward('denied', 'access', 'admin');
    }
    $id = $this->getRequest()->getParam('id');
    $category = Doctrine::getTable('Category')->find($id);
    if (!$id) {
      return $this->_forward('not-found', 'error', 'default');
    }
    $can_delete = !$category->getNode()->hasChildren();
    if ($can_delete) {
      $form = new Form_Delete();
      if ($this->getRequest()->isPost()) {
        if ($form->isValid($this->getRequest()->getPost())) {
          try {
            Doctrine::getTable('Category')->deleteCategory($category);
            $this->addMessage("Category {$category['name']} deleted.", 'success');
            return $this->routeRedirect("admin_category");
          } catch (Exception $e) {
            $this->logError("Error deleting category. - {$e->getMessage()}");
            $this->addMessage("Error deleting category", 'error');
            return $this->routeRedirect('admin_category_delete', array('id' => $id));
          }
        }
      }
      $this->view->form = $form;
    }
    $this->view->category = $category;
    $this->view->can_delete = $can_delete;
    // Activate Menu Items
    $container = Zend_Registry::get('Zend_Navigation');
    $found = $container->findOneByRoute('admin_category_delete');
    $found->setVisible(true);
    $found->set('params', array('id' => $id));
  }

}