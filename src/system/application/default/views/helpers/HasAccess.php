<?php
class Zend_View_Helper_HasAccess extends Zend_View_Helper_Abstract{

	/**
     * Checks if logged in user has access to the requested resource.
     * @param string $resource
     * @param string $privilege
     * @return bool
     */
	function hasAccess($resource='general', $privilege=null){
	  $auth = Zend_Auth::getInstance();
      $acl = Zend_Registry::get('acl');
      if(!$auth->hasIdentity()) return false;
      $identity = $auth->getIdentity();
      return $acl->isAllowed($identity->accesslevel, $resource, $privilege);
	}
}