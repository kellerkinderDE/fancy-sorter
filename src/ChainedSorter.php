<?php

namespace Kellerkinder\FancySorter;

use RuntimeException;
use InvalidArgumentException;

class ChainedSorter implements ChainedSorterInterface
{
  protected $sorters;
  protected $valueAccessor;

  public function __construct(array $sorters = [])
  {
    $filtered = array_filter(
      $sorters,
      function($sorter) {
        return $sorter instanceof SorterInterface;
      }
    );

    if (count($sorters) !== count($filtered)) {
      throw new InvalidArgumentException(
        sprintf(
          '%s does only accept an array of SorterInterface instances',
          __CLASS__
        )
      );
    }

    if (!$sorters) {
      $sorters = [
        new ClothingSizeSorter(),
        new JeansSizeSorter(),
        new NumericSorter(),
        new AlphanumericSorter()
      ];
    }

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