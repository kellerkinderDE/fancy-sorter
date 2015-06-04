<?php

namespace Kellerkinder\FancySorter;

class AlphanumericSorter implements SorterInterface
{
  public function sort(array $input)
  {
    usort($input, 'strcasecmp');
    return $input;
  }

  public function supports(array $input)
  {
    return true;
  }
}