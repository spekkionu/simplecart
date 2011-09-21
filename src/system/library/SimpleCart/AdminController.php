<?php
/**
 * Admin Controller base class
 *
 * @package    Simplecart
 * @subpackage Controller
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
abstract class SimpleCart_AdminController extends SimpleCart_Controller {

  /**
   * Controller init
   */
  public function init(){
    parent::init();
    // Set Admin Logger
    $this->logger = new Zend_Log(new Zend_Log_Writer_Stream($this->config->system.'/logs/admin.log'));
    // Set Admin Layout
    $this->_helper->layout->setLayoutPath($this->config->system . '/application/admin/views/layout');
    $this->view->pageHeader = NULL;
    // Setup Admin Navigation
    $pages = new Zend_Config_Yaml(SYSTEM.'/configs/navigation.yml');
    $container = new Zend_Navigation($pages);
    // Save Navigation
    Zend_Registry::set('Zend_Navigation', $container);
  }

}