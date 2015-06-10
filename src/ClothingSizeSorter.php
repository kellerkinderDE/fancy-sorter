<?php

namespace Kellerkinder\FancySorter;

use InvalidArgumentException;
use Closure;

class ClothingSizeSorter implements SpecificSorterInterface
{
  protected static $mapping = ['S' => -1, 'M' => 0, 'L' => 1];
  protected static $pattern = '!^((?P<count>\d)?(?P<x>X+))?(?P<size>[SL])$!';
  protected $valueAccessor;

  public function __construct(Closure $valueAccessor = null)
  {
    $this->valueAccessor = $valueAccessor ?: [$this, 'simpleValueAccessor'];
  }

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
        $value = call_user_func($this->valueAccessor, $value);
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

  protected function calculatePriority($value)
  {
    $value = call_user_func($this->valueAccessor, $value);
    $value = trim(strtoupper($value));
    $priority = NULL;

    if (array_key_exists($value, self::$mapping)) {
      return self::$mapping[$value];
    }

    preg_match(self::$pattern, $value, $matches);

    $priority = self::$mapping[$matches['size']];

    if ($matches['count'] === '') {
      $matches['count'] = strlen($matches['x']);
    }

    if ($matches['count']) {
      $priority += intval($matches['count']) * $priority;
    }

    return $priority;
  }

  protected function simpleValueAccessor($value)
  {
    return $value;
  }
}