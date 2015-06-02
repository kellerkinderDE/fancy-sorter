<?php

namespace FancySorter;

class JeansSizeSorter
{
  public function sort(array $input)
  {
    usort($input, 'strcasecmp');
    return $input;
  }
}