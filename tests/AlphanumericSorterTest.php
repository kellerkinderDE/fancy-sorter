<?php

namespace FancySorter\Tests;

use FancySorter\AlphanumericSorter;
use PHPUnit_Framework_TestCase;

class AlphanumericSorterTest extends PHPUnit_Framework_TestCase
{
  public function testSort()
  {
    $sorter = new AlphanumericSorter();

    $this->assertSame(
      ['Blue', 'Green', 'Red'],
      $sorter->sort(['Green', 'Red', 'Blue'])
    );
  }
}
