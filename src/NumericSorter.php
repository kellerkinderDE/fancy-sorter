<?php

namespace Kellerkinder\FancySorter;

use InvalidArgumentException;
use Closure;

class NumericSorter implements SpecificSorterInterface
{
  protected $valueAccessor;

  public function __construct(Closure $valueAccessor = null)
  {
    $this->valueAccessor = $valueAccessor ?: [$this, 'simpleValueAccessor'];
  }

  public function sort(array $input)
  {
    if (!$this->supports($input)) {
      throw new InvalidArgumentException(
        sprintf(
          '%s does not support sorting the following values: %s',
          __CLASS__,
          implode(', ', array_map('json_encode', $input))
        )
      );
    }

    usort($input, [$this, 'sortCallback']);
    return $input;
  }

  public function supports(array $input)
  {
    $input = array_map($this->valueAccessor, $input);
    return count($input) === count(array_filter($input, 'is_numeric'));
  }

  protected function sortCallback($a, $b)
  {
    $av = intval(call_user_func($this->valueAccessor, $a));
    $bv = intval(call_user_func($this->valueAccessor, $b));

    if ($av === $bv) {
      return 0;
    }

    return $av <= $bv ? -1 : 1;
  }

  protected function simpleValueAccessor($value)
  {
    return $value;
  }
}