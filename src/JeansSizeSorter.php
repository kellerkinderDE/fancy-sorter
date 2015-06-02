<?php

namespace FancySorter;

class JeansSizeSorter
{
  public function sort(array $input)
  {
    usort($input, [$this, 'sortCallback']);
    return $input;
  }

  protected function sortCallback($a, $b)
  {
    return strcasecmp(
      $this->normalizeValue($a),
      $this->normalizeValue($b)
    );
  }

  protected function normalizeValue($input)
  {
    return preg_replace('!^(\d+)W?\s*/\s*(\d+)L?$!', '$1/$2', trim($input));
  }
}