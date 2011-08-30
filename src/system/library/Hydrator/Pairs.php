<?php

class Hydrator_Pairs extends Doctrine_Hydrator_Abstract
{
    public function hydrateResultSet($stmt)
    {
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
