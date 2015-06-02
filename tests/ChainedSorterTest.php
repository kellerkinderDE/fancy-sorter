<?php

namespace FancySorter\Tests;

use FancySorter\ChainedSorter;
use FancySorter\ClothingSizeSorter;
use FancySorter\JeansSizeSorter;
use FancySorter\NumericSorter;
use FancySorter\AlphanumericSorter;
use PHPUnit_Framework_TestCase;

class ChainedSorterTest extends PHPUnit_Framework_TestCase
{
  /**
   * @dataProvider valueProvider
   */
  public function testSupports($input)
  {
    $sorter = new ChainedSorter([
      new ClothingSizeSorter(),
      new JeansSizeSorter(),
      new NumericSorter(),
      new AlphanumericSorter()
    ]);

    $this->assertTrue($sorter->supports($input));
  }

  /**
   * @dataProvider valueProvider
   */
  public function testSort($input, $expectedResult)
  {
    $sorter = new ChainedSorter([
      new ClothingSizeSorter(),
      new JeansSizeSorter(),
      new NumericSorter(),
      new AlphanumericSorter()
    ]);

    $this->assertSame(
      $expectedResult,
      $sorter->sort($input)
    );
  }

  public function valueProvider()
  {
    return [
      [
        ['32W/34L', '30/32'],
        ['30/32', '32W/34L']
      ],
      [
        [4, '3', 5, 1, 2],
        [1, 2, '3', 4, 5]
      ],
      [
        ['Green', 'Blue', 'Red'],
        ['Blue', 'Green', 'Red'],

      ],
      [
        ['M','L','S','XL','XS'],
        ['XS', 'S', 'M', 'L', 'XL']
      ]
    ];
  }
}