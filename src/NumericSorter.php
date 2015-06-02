<?php

namespace FancySorter;

use InvalidArgumentException;

class NumericSorter implements SorterInterface
{
  public function sort(array $input)
  {
    if (!$this->supports($input)) {
      throw new InvalidArgumentException();
    }

    sort($input);
    return $input;
  }

  public function supports(array $input)
  {
    return count($input) === count(array_filter($input, 'is_numeric'));
  }
}