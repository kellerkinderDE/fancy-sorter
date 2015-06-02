<?php

namespace FancySorter\Tests;

use FancySorter\ChainedSorter;
use FancySorter\ClothingSizeSorter;
use FancySorter\JeansSizeSorter;
use FancySorter\NumericSorter;
use FancySorter\AlphanumericSorter;
use PHPUnit_Framework_TestCase;
use ReflectionObject;

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

  /**
   * @dataProvider valueProvider
   */
  public function testInternalInstance($input, $_, $instanceClassname)
  {
    $sorter = new ChainedSorter([
      new ClothingSizeSorter(),
      new JeansSizeSorter(),
      new NumericSorter(),
      new AlphanumericSorter()
    ]);

    $reflectedSorter = new ReflectionObject($sorter);
    $reflectedGetSorter = $reflectedSorter->getMethod('getSorter');
    $reflectedGetSorter->setAccessible(true);

    $this->assertInstanceOf(
      $instanceClassname,
      $reflectedGetSorter->invoke($sorter, $input)
    );
  }

  public function valueProvider()
  {
    return [
      [
        ['32W/34L', '30/32'],
        ['30/32', '32W/34L'],
        'FancySorter\JeansSizeSorter'
      ],
      [
        [4, '3', 5, 1, 2],
        [1, 2, '3', 4, 5],
        'FancySorter\NumericSorter'
      ],
      [
        ['Green', 'Blue', 'Red'],
        ['Blue', 'Green', 'Red'],
        'FancySorter\AlphanumericSorter'

      ],
      [
        ['M','L','S','XL','XS'],
        ['XS', 'S', 'M', 'L', 'XL'],
        'FancySorter\ClothingSizeSorter'
      ]
    ];
  }
}