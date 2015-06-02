<?php

namespace FancySorter\Tests;

use FancySorter\NumericSorter;
use PHPUnit_Framework_TestCase;

class NumericSorterTest extends PHPUnit_Framework_TestCase
{
  public function testSort()
  {
    $sorter = new NumericSorter();

    $this->assertSame(
      [50, 52, 54, 96, 128],
      $sorter->sort([128, 54, 50, 96, 52])
    );
  }
}
