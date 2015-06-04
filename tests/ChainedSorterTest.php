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
  protected $sorter;

  protected function setUp()
  {
    $this->sorter = new ChainedSorter([
      new ClothingSizeSorter(),
      new JeansSizeSorter(),
      new NumericSorter(),
      new AlphanumericSorter()
    ]);
  }

  /**
   * @dataProvider valueProvider
   */
  public function testSupports($input)
  {
    $this->assertTrue($this->sorter->supports($input));
  }

  /**
   * @dataProvider valueProvider
   */
  public function testSort($input, $expectedResult)
  {
    $this->assertSame(
      $expectedResult,
      $this->sorter->sort($input)
    );
  }

  /**
   * @dataProvider valueProvider
   */
  public function testInternalInstance($input, $_, $instanceClassname)
  {
    $reflectedSorter = new ReflectionObject($this->sorter);
    $reflectedGetSorter = $reflectedSorter->getMethod('getSorter');
    $reflectedGetSorter->setAccessible(true);

    $this->assertInstanceOf(
      $instanceClassname,
      $reflectedGetSorter->invoke($this->sorter, $input)
    );
  }

  /**
   * @dataProvider valueProvider
   */
  public function testChainedWithOnlyAlphanumericSupportsEverything($input)
  {
    $this->sorter = new ChainedSorter([
      new AlphanumericSorter()
    ]);

    $this->assertTrue($this->sorter->supports($input));
  }

  public function testChainedWithOnlyNumeric()
  {
    $this->sorter = new ChainedSorter([
      new NumericSorter()
    ]);

    $this->assertFalse($this->sorter->supports(['32W/34L', '30/32']));
    $this->assertTrue($this->sorter->supports([4, '3', 5, 1, 2]));
  }

  /**
   * @expectedException RuntimeException
   * @expectedExceptionMessage FancySorter\ChainedSorter (containing FancySorter\NumericSorter) does not support sorting the following values: "32W\/34L", "30\/32"
   */
  public function testChainedWithOnlyNumericSort()
  {
    $this->sorter = new ChainedSorter([
      new NumericSorter()
    ]);

    $this->sorter->sort(['32W/34L', '30/32']);
  }

  public function testChainedWithOnlyJeansSize()
  {
    $this->sorter = new ChainedSorter([
      new JeansSizeSorter()
    ]);

    $this->assertFalse($this->sorter->supports(['M','L','S','XL','XS']));
    $this->assertTrue($this->sorter->supports(['32W/34L', '30/32']));
  }

  public function testChainedWithOnlyClothingSize()
  {
    $this->sorter = new ChainedSorter([
      new ClothingSizeSorter()
    ]);

    $this->assertFalse($this->sorter->supports(['Green', 'Blue', 'Red']));
    $this->assertTrue($this->sorter->supports(['M','L','S','XL','XS']));
  }

  /**
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage FancySorter\ChainedSorter does only accept an array of SorterInterface instances
   */
  public function testChainedInvalidInstance()
  {
    new ChainedSorter([
      new \stdClass()
    ]);
  }

  /**
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage FancySorter\ChainedSorter requires at least one instance of SorterInterface
   */
  public function testChainedEmptyArray()
  {
    new ChainedSorter([]);
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
        [96, 52, '128', 50, 54],
        [50, 52, 54, 96, '128'],
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