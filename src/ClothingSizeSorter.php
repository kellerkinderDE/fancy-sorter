<?php

namespace FancySorter;

class ClothingSizeSorter implements SorterInterface
{
  const MAPPING = ['S' => -1, 'M' => 0, 'L' => 1];
  const PATTERN = '!^((?P<count>\d)?(?P<x>X+))?(?P<size>[SL])$!';

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

    preg_match(self::PATTERN, $input, $matches);

    $priority = self::MAPPING[$matches['size']];

    if ($matches['count'] === '') {
      $matches['count'] = strlen($matches['x']);
    }

    if ($matches['count']) {
      $priority += intval($matches['count']) * $priority;
    }

    return $priority;
  }
}