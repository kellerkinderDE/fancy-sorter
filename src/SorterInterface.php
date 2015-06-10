<?php

namespace Kellerkinder\FancySorter;

interface SorterInterface
{
  public function sort(array $input);
  public function supports(array $input);
}