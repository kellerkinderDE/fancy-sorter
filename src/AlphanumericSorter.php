<?php

namespace FancySorter;

class AlphanumericSorter
{
  public function sort(array $input)
  {
    usort($input, 'strcasecmp');
    return $input;
  }
}