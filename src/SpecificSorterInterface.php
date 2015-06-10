<?php

namespace Kellerkinder\FancySorter;

use Closure;

interface SpecificSorterInterface extends SorterInterface
{
  public function __construct(Closure $valueAccessor = null);
}