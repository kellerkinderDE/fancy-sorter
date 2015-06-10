<?php

namespace Kellerkinder\FancySorter;

use Closure;

class AlphanumericSorter implements SpecificSorterInterface
{
  protected $valueAccessor;

  public function __construct(Closure $valueAccessor = null)
  {
    $this->valueAccessor = $valueAccessor ?: [$this, 'simpleValueAccessor'];
  }

  public function sort(array $input)
  {
    usort($input, [$this, 'sortCallback']);
    return $input;
  }

  public function supports(array $input)
  {
    return true;
  }

  protected function sortCallback($a, $b)
  {
    return strcasecmp(
      call_user_func($this->valueAccessor, $a),
      call_user_func($this->valueAccessor, $b)
    );
  }

  protected function simpleValueAccessor($value)
  {
    return $value;
  }
}