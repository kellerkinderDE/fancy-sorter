<?php

namespace FancySorter;

class NumericSorter implements SorterInterface
{
  public function sort(array $input)
  {
    sort($input);
    return $input;
  }
}