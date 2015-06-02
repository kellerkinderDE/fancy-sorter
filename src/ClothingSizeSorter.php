<?php

namespace FancySorter;

class ClothingSizeSorter implements SorterInterface
{
  const MAPPING = ['S' => -1, 'M' => 0, 'L' => 1];
  const PATTERN = '!^((\d)?(X+))?([SL])$!';

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
        return array_key_exists($value, self::MAPPING) ||
               preg_match(self::PATTERN, $value);
      }
    );

    return count($input) === count($filtered);
  }

  protected function sortCallback($a, $b)
  {
    $av = $this->calculatePriority($a);
    $bv = $this->calculatePriority($b);
    
    if ($av === $bv)
      return 0;

    return $av <= $bv ? -1 : 1;
  }

  protected function calculatePriority($input)
  {
    $input = trim(strtoupper($input));
    $priority = NULL;

    if (array_key_exists($input, self::MAPPING)) {
      return self::MAPPING[$input];
    }

    preg_match(
      self::PATTERN,
      $input,
      $matches
    );

    $priority = self::MAPPING[$matches[4]];

    if ($matches[2] === '') {
      $matches[2] = strlen($matches[3]);
    }

    if ($matches[2]) {
      $priority += intval($matches[2]) * $priority;
    }

    return $priority;
  }
}