<?php

/**
 * Doctrine Adapter for Zend_Auth
 *
 * @category   Zend
 * @package    Zend_Auth
 * @subpackage Zend_Auth_Adapter
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @version 2.0 08/20/2011
 *
 */
class Auth_Adapter_Doctrine implements Zend_Auth_Adapter_Interface {

  /**
   * $_tableName - the table name to check
   *
   * @var string
   */
  protected $_tableName = null;

  /**
   * $_identityColumn - the column to use as the identity
   *
   * @var string
   */
  protected $_identityColumn = null;

  /**
   * $_credentialColumns - columns to be used as the credentials
   *
   * @var string
   */
  protected $_credentialColumn = null;

  /**
   * $_identity - Identity value
   *
   * @var string
   */
  protected $_identity = null;

  /**
   * $_credential - Credential values
   *
   * @var string
   */
  protected $_credential = null;

  /**
   * $_authenticateResultInfo
   *
   * @var array
   */
  protected $_authenticateResultInfo = null;

  /**
   * $_resultRow - Results of database authentication query
   *
   * @var array
   */
  protected $_resultRow = null;

  /**
   * Doctrine query for finding user account
   * @var Doctrine_Query $_query
   */
  protected $_query = null;

  /**
   *
   *@param string     $tableName
   *@param string     $identityColumn
   *@param string     $credentialColumn
   *@return void
   */
  public function __construct($tableName = null, $identityColumn = null, $credentialColumn = null) {
    
    if(null !== $tableName) {
      $this->setTableName($tableName);
    }
    
    if(null !== $identityColumn) {
      $this->setIdentityColumn($identityColumn);
    }
    
    if(null !== $credentialColumn) {
      $this->setCredentialColumn($credentialColumn);
    }
    
    $this->createQuery();
  }

  /**
   * Sets Doctrine Query
   * @return Doctrine_Query
   */
  protected function createQuery() {
    // Create Doctrine Query
    $q = Doctrine_Query::create();
    $q->from($this->_tableName);
    $q->where("{$this->_identityColumn} = :username");
    $q->where("{$this->_credentialColumn} = :password");
    $this->_query = $q;
    return $q;
  }

  /**
   * getDoctrineQuery() - Return the preauthentication Db Select object for userland select query modification
   *
   * @return Doctrine_Query
   */
  public function getDoctrineQuery() {
    if($this->_query == null) {
      $this->_query = $this->createQuery();
    }
    return $this->_query;
  }

  /**
   *
   * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
   * @return Zend_Auth_Result
   * @see Zend_Auth_Adapter_Interface::authenticate()
   */
  public function authenticate() {
    $this->_authenticateSetup();
    $tableName = $this->_tableName;
    $identityColumn = $this->_identityColumn;
    $credentialColumn = $this->_credentialColumn;
    // Execute Query
    $identity = $this->getDoctrineQuery()
      ->fetchOne(array(
      ':username' => $this->_identity, ':password' => $this->_credential
    ), Doctrine::HYDRATE_ARRAY);
    if(!$identity) {
      // Not found
      return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->_identity, array(
        "Account with username $this->_identity not found."
      ));
    }
    $this->_resultRow = $identity;
    return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_identity, array());
  }

  /**
   * _authenticateSetup() - This method abstracts the steps involved with making sure
   * that this adapter was indeed setup properly with all required peices of information.
   *
   * @throws Zend_Auth_Adapter_Exception - in the event that setup was not done properly
   * @return true
   */
  protected function _authenticateSetup() {
    $exception = null;
    
    if($this->_tableName == '') {
      $exception = 'A table must be supplied for the Auth_Doctrine authentication adapter.';
    } elseif($this->_identityColumn == '') {
      $exception = 'An identity column must be supplied for the Auth_Doctrine authentication adapter.';
    } elseif($this->_credentialColumn == '') {
      $exception = 'A credential column must be supplied for the Auth_Doctrine authentication adapter.';
    } elseif($this->_identity == '') {
      $exception = 'A value for the identity was not provided prior to authentication with Auth_Doctrine.';
    } elseif($this->_credential === null) {
      $exception = 'A credential value was not provided prior to authentication with Auth_Doctrine.';
    }
    if(is_null($this->_query)) {
      $this->createQuery();
    }
    
    if(null !== $exception) {
      /**
       * @see Zend_Auth_Adapter_Exception
       */
      throw new Zend_Auth_Adapter_Exception($exception);
    }
    
    $this->_authenticateResultInfo = array(
      'code' => Zend_Auth_Result::FAILURE, 'identity' => $this->_identity, 'messages' => array()
    );
    
    return true;
  }

  /**
   * setTableName() - set the table name to be used in the select query
   *
   * @param  string $tableName
   * @return Auth_Adapter_Doctrine Provides a fluent interface
   */
  public function setTableName($tableName) {
    $this->_tableName = $tableName;
    return $this;
  }

  /**
   * setIdentityColumn() - set the column name to be used as the identity column
   *
   * @param  string $identityColumn
   * @return Auth_Adapter_Doctrine Provides a fluent interface
   */
  public function setIdentityColumn($identityColumn) {
    $this->_identityColumn = $identityColumn;
    return $this;
  }

  /**
   * setCredentialColumn() - set the column name to be used as the credential column
   *
   * @param  string $credentialColumn
   * @return Auth_Adapter_Doctrine Provides a fluent interface
   */
  public function setCredentialColumn($credentialColumn) {
    $this->_credentialColumn = $credentialColumn;
    return $this;
  }

  /**
   * setIdentity() - set the value to be used as the identity
   *
   * @param  string $value
   * @return Auth_Adapter_Doctrine Provides a fluent interface
   */
  public function setIdentity($value) {
    $this->_identity = $value;
    return $this;
  }

  /**
   * setCredential() - set the credential value to be used
   *
   * @param  string $credential
   * @return Auth_Adapter_Doctrine Provides a fluent interface
   */
  public function setCredential($credential) {
    $this->_credential = $credential;
    return $this;
  }

  /**
   * getResultRowObject() - Returns the result row as a stdClass object
   *
   * @param  string|array $returnColumns
   * @param  string|array $omitColumns
   * @return stdClass|boolean
   */
  public function getResultRowObject($returnColumns = null, $omitColumns = null) {
    if(!$this->_resultRow) {
      return false;
    }
    
    $returnObject = new stdClass();
    
    if(null !== $returnColumns) {
      
      $availableColumns = array_keys($this->_resultRow);
      foreach ((array) $returnColumns as $returnColumn) {
        if(in_array($returnColumn, $availableColumns)) {
          $returnObject->{$returnColumn} = $this->_resultRow[$returnColumn];
        }
      }
      return $returnObject;
    
    } elseif(null !== $omitColumns) {
      
      $omitColumns = (array) $omitColumns;
      foreach ($this->_resultRow as $resultColumn => $resultValue) {
        if(!in_array($resultColumn, $omitColumns)) {
          $returnObject->{$resultColumn} = $resultValue;
        }
      }
      return $returnObject;
    
    } else {
      
      foreach ($this->_resultRow as $resultColumn => $resultValue) {
        $returnObject->{$resultColumn} = $resultValue;
      }
      return $returnObject;
    
    }
  }
}

