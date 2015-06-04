<?php

namespace Kellerkinder\FancySorter;

use InvalidArgumentException;

class NumericSorter implements SorterInterface
{
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
    return count($input) === count(array_filter($input, 'is_numeric'));
  }
}