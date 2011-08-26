<?php
/**
 * Date Format Helper
 *
 */

/**
 * Date Format Helper Class
 *
 */
class Zend_View_Helper_DateFormat{
	
	/**
	 * Formats a date and returns it as a string
	 * Uses Zend_Date
	 * 
	 * @param string $date
	 * @param string $format format matching date()
	 * @return string
	 */
	function DateFormat($date, $format="m/d/Y h:i:s A"){
		if(empty($date)){
			return "";
		}
		Zend_Date::setOptions(array('format_type' => 'php'));
		$zdate = new Zend_Date(strtotime($date));
		$str_date = $zdate->toString($format);
		Zend_Date::setOptions(array('format_type' => 'iso'));
		return $str_date;
	}
}