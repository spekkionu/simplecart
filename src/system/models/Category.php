<?php

/**
 * Category
 *
 * @package    Simplecart
 * @subpackage Model
 * @author     spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Category extends Base_Category
{

  public function setPath($path) {
    return $this->_set('path', self::urlize($path));
  }

  public function preSave($event) {
    if ($this->_get('level') == 0) {
      // This is the root category, no path is necessary
      $this->_set('path', NULL);
    }
  }

  /**
   * Convert any passed string to a url friendly string. Converts 'My first blog post' to 'my-first-blog-post'
   *
   * @param  string $text Text to urlize
   * @return string $text Urlized text
   */
  public static function urlize($text) {
    // Replace path separator with constant
    $text = preg_replace('/\//', '__SEPARATOR__', $text);
    // Remove all non url friendly characters with the unaccent function
    $text = Doctrine_Inflector::unaccent($text);
    if (function_exists('mb_strtolower')) {
      $text = mb_strtolower($text);
    } else {
      $text = strtolower($text);
    }
    // Remove all none word characters
    $text = preg_replace('/\W\./', ' ', $text);
    // Reinsert path separator
    $text = preg_replace('/__SEPARATOR__/i', '::', $text);
    // More stripping. Replace spaces with dashes
    $text = preg_replace('/[^A-Z^a-z^0-9^\/\.]+/', '-', preg_replace('/([a-z\d])([A-Z])/', '\1_\2', preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2', preg_replace('/::/', '/', $text))));
    // Replace multiple separators with one.
    $text = preg_replace("/-{2,}/", '-', $text);
    return trim($text, '-/.');
  }

}
