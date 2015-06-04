<?php

namespace Kellerkinder\FancySorter\Tests;

use Kellerkinder\FancySorter\AlphanumericSorter;
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

  /**
   * AlphanumericSorter is supposed to be able to support _everything_
   * since the plan is, that if someone else is better suited to sort
   * it, he/she should speak up and take it.
   */
  public function testSupports()
  {
    $sorter = new AlphanumericSorter();
    $this->assertTrue($sorter->supports(['whatever']));
  }
}
