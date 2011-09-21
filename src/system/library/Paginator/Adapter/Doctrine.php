<?php
/**
 * Doctrine_Query adaptor for Zend_Paginator
 *
 * @package    Simplecart
 * @subpackage Zend_Paginator_Adapter
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Paginator_Adapter_Doctrine implements Zend_Paginator_Adapter_Interface {

  /**
   * Doctrine Query Instance
   * @var Doctrine_Query $_query
   */
  protected $_query = null;

  /**
   * Total item count
   *
   * @var integer
   */
  protected $_rowCount = null;

  /**
   * Class constructor
   * @param Doctrine_Query $query
   */
  public function __construct(Doctrine_Query $query){
    $this->_query = $query;
  }


  /**
   * Returns total number of items
   *
   * @return integer
   */
  public function count(){
    if(is_null($this->_rowCount)){
      $query = $this->getCountSelect();
      $this->_rowCount = (int) $query->fetchOne();
    }
    return $this->_rowCount;
  }

  /**
   * Returns DQL for count select
   * @return Doctrine_Query
   */
  public function getCountSelect(){
    // Clone the query so as not to overwrite the original query
    $query = clone($this->_query);
    // Overwrite select with count
    $query->select("COUNT(*) AS num_items");
    // Set limit and offset
    $query->limit(1);
    $query->offset(0);
    $query->setHydrationMode(Doctrine::HYDRATE_SINGLE_SCALAR);
    return $query;
  }

  /**
   * Returns select query
   * @return Doctrine_Query
   */
  public function getSelect(){
    return $this->_query;
  }

  /**
   * Returns an collection of items for a page.
   *
   * @param  integer $offset Page offset
   * @param  integer $itemCountPerPage Number of items per page
   * @return array
   */
  public function getItems($offset, $itemCountPerPage){
    $this->_query->limit($itemCountPerPage);
    $this->_query->offset($offset);
    $this->_query->setHydrationMode(Doctrine::HYDRATE_ARRAY);
    return $this->_query->execute();
  }

}