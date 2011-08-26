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
    $paginator->setDefaultScrollingStyle('Sliding');
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage($rowsPerPage);
    Zend_View_Helper_PaginationControl::setDefaultViewPartial(
        array('partials/pagination.phtml','default')
    );
    return $paginator;
	}
}