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

  public function validInputProvider()
  {
    return [
      [[1, 2, 3, 4, 5]],
      [['1', '2', '3', '4', '5']]
    ];
  }

  /**
   * @dataProvider validInputProvider
   */
  public function testDoesSupport($input)
  {
    $sorter = new NumericSorter();
    $this->assertTrue($sorter->supports($input));
  }

  public function invalidInputProvider()
  {
    return [
      [['32/34', 2, 3, 4, 5]],
      [['32W/34L', 2, 3, 4, 5]],
      [['32W/34L', 2, 3, 4, 5]]
    ];
  }

  /**
   * @dataProvider invalidInputProvider
   */
  public function testDoesNotSupport($input)
  {
    $sorter = new NumericSorter();
    $this->assertFalse($sorter->supports($input));
  }

  /**
   * @dataProvider invalidInputProvider
   * @expectedException InvalidArgumentException
   */
  public function testInvalidSort($input)
  {
    $sorter = new NumericSorter();
    $sorter->sort($input);
  }
}
