<?php

/**
 * Base_User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $password
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property boolean $active
 * @property integer $billing_id
 * @property integer $shipping_id
 * @property timestamp $signup_date
 * @property timestamp $last_login
 * @property string $token
 * @property string $password_key
 * @property date $token_date
 * @property Address $Billing
 * @property Address $Shipping
 * @property Order $Orders
 * 
 * @package    SimpleCart
 * @subpackage Models
 * @author     Jonathan Bernardi <spekkionu@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Base_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('password', 'string', 128, array(
             'type' => 'string',
             'fixed' => 1,
             'notblank' => true,
             'notnull' => true,
             'length' => '128',
             ));
        $this->hasColumn('email', 'string', 127, array(
             'type' => 'string',
             'email' => true,
             'notnull' => true,
             'notblank' => true,
             'unique' => true,
             'length' => '127',
             ));
        $this->hasColumn('firstname', 'string', 32, array(
             'type' => 'string',
             'notblank' => true,
             'notnull' => true,
             'length' => '32',
             ));
        $this->hasColumn('lastname', 'string', 64, array(
             'type' => 'string',
             'notblank' => true,
             'notnull' => true,
             'length' => '64',
             ));
        $this->hasColumn('active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'notnull' => true,
             'unsigned' => true,
             ));
        $this->hasColumn('billing_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('shipping_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('signup_date', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             ));
        $this->hasColumn('last_login', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('token', 'string', 128, array(
             'type' => 'string',
             'fixed' => 1,
             'length' => '128',
             ));
        $this->hasColumn('password_key', 'string', 128, array(
             'type' => 'string',
             'fixed' => 1,
             'length' => '128',
             ));
        $this->hasColumn('token_date', 'date', null, array(
             'type' => 'date',
             ));


        $this->index('login', array(
             'fields' => 
             array(
              0 => 'email',
              1 => 'password',
              2 => 'active',
             ),
             'type' => 'unique',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Address as Billing', array(
             'local' => 'billing_id',
             'foreign' => 'id',
             'owningSide' => true));

        $this->hasOne('Address as Shipping', array(
             'local' => 'shipping_id',
             'foreign' => 'id',
             'owningSide' => true));

        $this->hasOne('Order as Orders', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             'created' => 
             array(
              'name' => 'signup_date',
              'type' => 'timestamp',
             ),
             'updated' => 
             array(
              'disabled' => true,
             ),
             ));
        $this->actAs($timestampable0);
    }
}