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
      ['XS', 'S', 'M', 'L', 'L', 'XL'],
      $sorter->sort(['M', 'L', 'S', 'XL', 'XS', 'L'])
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
      [
        ['32/34', '32W/34L'],
        'FancySorter\ClothingSizeSorter does not support sorting the following values: "32\/34", "32W\/34L"'
      ],
      [
        [1, 2, 3, 4, 5],
        'FancySorter\ClothingSizeSorter does not support sorting the following values: 1, 2, 3, 4, 5'
      ],
      [
        ['Green', 'Blue', 'Red'],
        'FancySorter\ClothingSizeSorter does not support sorting the following values: "Green", "Blue", "Red"'
      ]
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

  /**
   * @dataProvider invalidInputProvider
   */
  public function testInvalidSort($input, $expectedExceptionMessage)
  {
    $this->setExpectedException('InvalidArgumentException', $expectedExceptionMessage);

    $sorter = new ClothingSizeSorter();
    $sorter->sort($input);
  }
}
