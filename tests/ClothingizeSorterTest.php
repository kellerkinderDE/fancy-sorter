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

  public function validInputProvider()
  {
    return [
      [['M', 'L', 'S', 'XS', '3XL']]
    ];
  }

  /**
   * @dataProvider validInputProvider
   */
  public function testDoesSupport($input)
  {
    $sorter = new ClothingSizeSorter();
    $this->assertTrue($sorter->supports($input));
  }

  public function invalidInputProvider()
  {
    return [
      [['32/34', '32W/34L']], // JeansSize
      [[1, 2, 3, 4, 5]], // Numeric
      [['Green', 'Blue', 'Red']] // Alphanumeric
    ];
  }

  /**
   * @dataProvider invalidInputProvider
   */
  public function testDoesNotSupport($input)
  {
    $sorter = new ClothingSizeSorter();
    $this->assertFalse($sorter->supports($input));
  }
}
