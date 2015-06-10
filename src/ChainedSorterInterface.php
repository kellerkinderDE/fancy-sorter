<?php

namespace Kellerkinder\FancySorter;

use Closure;

interface ChainedSorterInterface extends SorterInterface
{
  public function __construct(array $sorters);
}