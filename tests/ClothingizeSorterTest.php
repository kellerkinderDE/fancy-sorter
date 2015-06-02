<?php

namespace FancySorter\Tests;

use FancySorter\ClothingSizeSorter;
use PHPUnit_Framework_TestCase;

class ClothingSizeSorterTest extends PHPUnit_Framework_TestCase
{
  public function testSort()
  {
    $sorter = new ClothingSizeSorter();

    $this->assertSame(
      ['XS', 'S', 'M', 'L', 'XL'],
      $sorter->sort(['M','L','S','XL','XS'])
    );
  }
}
