<?php

namespace FancySorter;

class JeansSizeSorter implements SorterInterface
{
  const PATTERN = '!^(\d+)W?\s*/\s*(\d+)L?$!';

  public function sort(array $input)
  {
    usort($input, [$this, 'sortCallback']);
    return $input;
  }

  public function supports(array $input)
  {
    $filtered = array_filter(
      $input,
      function($value) {
        return preg_match(self::PATTERN, $value);
      }
    );

    return count($input) === count($filtered);
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
    return preg_replace(self::PATTERN, '$1/$2', trim($input));
  }
}