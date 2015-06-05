<?php

namespace Kellerkinder\FancySorter;

use InvalidArgumentException;

class JeansSizeSorter implements SorterInterface
{
  protected static $pattern = '!^(?P<width>\d+)W?\s*/\s*(?P<length>\d+)L?$!';

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

  protected function normalizeValue($input)
  {
    return preg_replace(self::$pattern, '$1/$2', trim($input));
  }
}