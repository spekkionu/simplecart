<?php


class SimpleCart_Paginator {

	private function __construct(){}

	/**
	 * Generates the Pagination Instance
	 *
	 * @param Zend_Db_Select $query
	 * @param integer $page The Current Page
	 * @param integer $rowsPerPage The number of rows per page
	 * @return Zend_Paginator
	 */
	public static function getPagination(Zend_Db_Select $query, $page, $rowsPerPage){
		$paginator = Zend_Paginator::factory($query);
    self::_setup($paginator);
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage($rowsPerPage);
    return $paginator;
	}

  /**
	 * Generates the Pagination Instance
	 *
	 * @param Doctrine_Query $query
	 * @param integer $page The Current Page
	 * @param integer $rowsPerPage The number of rows per page
	 * @return Zend_Paginator
	 */
  public static function getDoctrinePaginator(Doctrine_Query $query, $page, $rowsPerPage){
    $paginator = new Zend_Paginator(new Paginator_Adapter_Doctrine($query));
    self::_setup($paginator);
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage($rowsPerPage);
    return $paginator;
  }

  /**
   * Sets paginator defaults
   * @param Zend_Paginator $paginator
   * @return Zend_Paginator
   */
  private static function _setup($paginator){
    $paginator->setDefaultScrollingStyle('Sliding');
    Zend_View_Helper_PaginationControl::setDefaultViewPartial(
        array('partials/pagination.phtml','default')
    );
    return $paginator;
  }
}