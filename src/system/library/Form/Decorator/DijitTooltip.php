<?php

class Form_Decorator_DijitTooltip extends Zend_Form_Decorator_Description {


  /**
   * Get the tooltip position
   *
   * @return string
   */
  public function getPosition()
  {
    return $this->getOption('position');
  }


  public function render($content) {
    $element = $this->getElement();
    $view = $element->getView();
    if (null === $view) {
        return $content;
    }

    $description = $element->getDescription();
    $description = trim($description);

    if (!empty($description) && (null !== ($translator = $element->getTranslator()))) {
        $description = $translator->translate($description);
    }

    if (empty($description)) {
        return $content;
    }

    $separator = $this->getSeparator();
    $placement = $this->getPlacement();
    $class     = $this->getClass();
    $escape    = $this->getEscape();
    $id        = $element->getId();
    $position  = $this->getPosition();

    $options   = $this->getOptions();

    if ($escape) {
        $description = $view->escape($description);
    }

    $tooltip_id = $id.'-tooltip';



    $output = '<span id="'.$tooltip_id.'"';
    if($class){
      $output .= ' class="'.$class.'"';
    }
    $output .= '></span>';

    $data = array(
      'dojoType' => 'dijit.Tooltip',
      'connectId' => $tooltip_id
    );
    if($position){
      $data['position'] = $position;
    }

    $output .= $view->customDijit($tooltip_id.'-content', $description, $data);



    switch ($placement) {
        case self::PREPEND:
            return $output . $separator . $content;
        case self::APPEND:
        default:
            return $content . $separator . $output;
    }

  }
}