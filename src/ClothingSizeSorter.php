<?php

namespace FancySorter;

class ClothingSizeSorter
{
  protected $mapping = ['S' => -1, 'M' => 0, 'L' => 1];

  public function sort(array $input)
  {
    usort($input, [$this, 'sortCallback']);
    return $input;
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

    if (array_key_exists($input, $this->mapping)) {
      return $this->mapping[$input];
    }

    preg_match(
      '!^((\d)?(X+))?([SL])$!',
      $input,
      $matches
    );

    $priority = $this->mapping[$matches[4]];

    if ($matches[2] === '') {
      $matches[2] = strlen($matches[3]);
    }

    if ($matches[2]) {
      $priority += intval($matches[2]) * $priority;
    }

    return $priority;
  }
}