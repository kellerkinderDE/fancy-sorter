<?php

namespace FancySorter;

class AlphanumericSorter implements SorterInterface
{
  public function sort(array $input)
  {
    usort($input, 'strcasecmp');
    return $input;
  }
}