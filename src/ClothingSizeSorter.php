<?php

namespace Kellerkinder\FancySorter;

use InvalidArgumentException;

class ClothingSizeSorter implements SorterInterface
{
  protected static $mapping = ['S' => -1, 'M' => 0, 'L' => 1];
  protected static $pattern = '!^((?P<count>\d)?(?P<x>X+))?(?P<size>[SL])$!';

  public function sort(array $input)
  {
    if (!$this->supports($input)) {
      throw new InvalidArgumentException(
        sprintf(
          '%s does not support sorting the following values: %s',
          __CLASS__,
          implode(', ', array_map('json_encode', $input))
        )
      );
    }

    usort($input, [$this, 'sortCallback']);
    return $input;
  }

  public function supports(array $input)
  {
    $filtered = array_filter(
      $input,
      function($value) {
        return array_key_exists($value, self::$mapping) ||
               preg_match(self::$pattern, $value);
      }
    );

    return count($input) === count($filtered);
  }

  protected function sortCallback($a, $b)
  {
    $av = $this->calculatePriority($a);
    $bv = $this->calculatePriority($b);
    
    if ($av === $bv) {
      return 0;
    }

    return $av <= $bv ? -1 : 1;
  }

  protected function calculatePriority($input)
  {
    $input = trim(strtoupper($input));
    $priority = NULL;

    if (array_key_exists($input, self::$mapping)) {
      return self::$mapping[$input];
    }

    preg_match(self::$pattern, $input, $matches);

    $priority = self::$mapping[$matches['size']];

    if ($matches['count'] === '') {
      $matches['count'] = strlen($matches['x']);
    }

    if ($matches['count']) {
      $priority += intval($matches['count']) * $priority;
    }

    return $priority;
  }
}