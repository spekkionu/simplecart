<?php
/**
 * Hydrates as key=>value pairs
 *
 * @package    Simplecart
 * @subpackage Doctrine_Hydrator
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Hydrator_Pairs extends Doctrine_Hydrator_Abstract
{
    public function hydrateResultSet($stmt)
    {
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
