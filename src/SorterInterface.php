<?php

namespace Kellerkinder\FancySorter;

interface SorterInterface
{
  function sort(array $input);
  function supports(array $input);
}