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

    sort($input, SORT_NUMERIC);
    return $input;
  }

  public function supports(array $input)
  {
    $input = array_map($this->valueAccessor, $input);
    return count($input) === count(array_filter($input, 'is_numeric'));
  }

  protected function simpleValueAccessor($value)
  {
    return $value;
  }
}