<?php

namespace Kellerkinder\FancySorter\Tests;

use Kellerkinder\FancySorter\NumericSorter;
use PHPUnit_Framework_TestCase;

class NumericSorterTest extends PHPUnit_Framework_TestCase
{
  public function validInputProvider()
  {
    return [
      [
        [5, 3, 4, 2, 1],
        [1, 2, 3, 4, 5]
      ],
      [
        ['5', '3', 4, '2', '1'],
        ['1', '2', '3', 4, '5']
      ],
      [
        [128, 54, 50, 96, 52],
        [50, 52, 54, 96, 128]
      ],
      [
        ['128', '54', '50', '96', '52'],
        ['50', '52', '54', '96', '128']
      ]
    ];
  }

  /**
   * @dataProvider validInputProvider
   */
  public function testSort($input, $expectedResult)
  {
    $sorter = new NumericSorter();

    $this->assertSame(
      $expectedResult,
      $sorter->sort($input)
    );
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
      [
        ['32/34', 2, 3, 4, 5],
        'FancySorter\NumericSorter does not support sorting the following values: "32\/34", 2, 3, 4, 5'
      ],
      [
        ['32W/34L', 2, 3, 4, 5],
        'FancySorter\NumericSorter does not support sorting the following values: "32W\/34L", 2, 3, 4, 5'
      ],
      [
        ['32W / 34L', 2, 3, 4, 5],
        'FancySorter\NumericSorter does not support sorting the following values: "32W \/ 34L", 2, 3, 4, 5'
      ]
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
   */
  public function testInvalidSort($input, $expectedExceptionMessage)
  {
    $this->setExpectedException('InvalidArgumentException', $expectedExceptionMessage);

    $sorter = new NumericSorter();
    $sorter->sort($input);
  }

  public function testArraySort()
  {
    $sorter = new NumericSorter(
      function($value) {
        return $value['optionname'];
      }
    );

    $this->assertSame(
      [
        ['optionname' => '52'],
        ['optionname' => '96'],
        ['optionname' => '128']
      ],
      $sorter->sort(
        [
          20 => ['optionname' => '128'],
          10 => ['optionname' => '96'],
          11 => ['optionname' => '52']
        ]
      )
    );
  }
}
