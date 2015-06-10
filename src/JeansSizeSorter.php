<?php

namespace Kellerkinder\FancySorter;

use InvalidArgumentException;
use Closure;

class JeansSizeSorter implements SpecificSorterInterface
{
  protected static $pattern = '!^(?P<width>\d+)W?\s*/\s*(?P<length>\d+)L?$!';
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
        return preg_match(self::$pattern, $value);
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

  protected function normalizeValue($value)
  {
    return preg_replace(
      self::$pattern,
      '$1/$2',
      trim(call_user_func($this->valueAccessor, $value))
    );
  }

  protected function simpleValueAccessor($value)
  {
    return $value;
  }
}