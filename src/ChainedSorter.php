<?php

namespace FancySorter;

class ChainedSorter implements SorterInterface
{
  protected $sorters;

  public function __construct(array $sorters)
  {
    $this->sorters = $sorters;
  }

  public function sort(array $input)
  {
    $sorter = $this->getSorter($input);
    return $sorter->sort($input);
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