<?php

namespace FancySorter;

class NumericSorter implements SorterInterface
{
  public function sort(array $input)
  {
    sort($input);
    return $input;
  }

  public function supports(array $input)
  {
    return count($input) === count(array_filter($input, 'is_numeric'));
  }
}