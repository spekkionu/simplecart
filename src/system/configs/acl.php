<?php

$acl = new Zend_Acl();


$acl->addRole(new Zend_Acl_Role('admin'));
$acl->addRole(new Zend_Acl_Role('superadmin'));
$acl->addRole(new Zend_Acl_Role('customer'));
$acl->addRole(new Zend_Acl_Role('guest'));

// Admin Resources
$acl->addResource('admin');
$acl->addResource('admin:category');
$acl->addResource('admin:product');
$acl->addResource('admin:order');
$acl->addResource('admin:report');
$acl->addResource('admin:settings');
$acl->addResource('admin:tax');
$acl->addResource('admin:shipping');
$acl->addResource('admin:payment');


// Front End Resources
$acl->addResource('checkout');

$acl->allow('superadmin');
$acl->allow('admin');

// Administrators cannot checkout
$acl->deny('superadmin', 'checkout');
$acl->deny('admin', 'checkout');

$acl->allow('customer', 'checkout');
$acl->allow('guest', 'checkout');

return $acl;