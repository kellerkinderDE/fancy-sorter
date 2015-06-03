<?php

namespace FancySorter;

use RuntimeException;

class ChainedSorter implements SorterInterface
{
  protected $sorters;

  public function __construct(array $sorters)
  {
    $this->sorters = $sorters;
  }

  public function sort(array $input)
  {
    if ($sorter = $this->getSorter($input)) {
      return $sorter->sort($input);
    }

    throw new RuntimeException(
      sprintf(
        '%s (containing %s) does not support sorting the following values: %s',
        __CLASS__,
        implode(', ', array_map('get_class', $this->sorters)),
        implode(', ', array_map('json_encode', $input))
      )
    );
  }

  public function supports(array $input)
  {
    return boolval($this->getSorter($input));
  }

  protected function getSorter(array $input)
  {
    foreach ($this->sorters as $sorter) {
      if ($sorter->supports($input)) {
        return $sorter;
      }
    }

    return null;
  }
}