<?php

namespace FancySorter;

interface SorterInterface
{
  function sort(array $input);
  function supports(array $input);
}