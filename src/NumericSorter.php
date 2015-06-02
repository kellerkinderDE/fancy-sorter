<?php

namespace FancySorter;

class NumericSorter
{
  public function sort(array $input)
  {
    sort($input);
    return $input;
  }
}