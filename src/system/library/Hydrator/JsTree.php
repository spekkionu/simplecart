<?php

class Hydrator_JsTree extends Doctrine_Hydrator_ArrayDriver {

  public function hydrateResultSet($stmt) {
    $collection = parent::hydrateResultSet($stmt);

    $table = $this->getRootComponent();

    if (!$table->isTree() || !$table->hasColumn('level')) {
      throw new Doctrine_Exception('Cannot hydrate model that does not implements Tree behavior with `level` column');
    }

    // Trees mapped
    $trees = array();
    $l = 0;

    if (count($collection) > 0) {
      // Node Stack. Used to help building the hierarchy
      $stack = array();

      foreach ($collection as $child) {
        $item = $child;

        $item['metadata']['id'] = $child['id'];
        $item['data']['title'] = $item['name'];
        $item['state'] = 'open';
        $item['type'] = 'category';
        $item['children'] = array();

        // Number of stack items
        $l = count($stack);

        // Check if we're dealing with different levels
        while ($l > 0 && $stack[$l - 1]['level'] >= $item['level']) {
          array_pop($stack);
          $l--;
        }

        // Stack is empty (we are inspecting the root)
        if ($l == 0) {
          // Assigning the root child
          $i = count($trees);
          $item['type'] = 'root';
          $trees[$i] = $item;
          $stack[] = & $trees[$i];
        } else {
          // Add child to parent
          $i = count($stack[$l - 1]['children']);
          $stack[$l - 1]['children'][$i] = $item;
          // Node is a leaf if it has no children
          $stack[] = & $stack[$l - 1]['children'][$i];
        }
      }
    }
    return $trees;
  }

}
