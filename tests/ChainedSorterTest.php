<?php

namespace Kellerkinder\FancySorter\Tests;

use Kellerkinder\FancySorter\ChainedSorter;
use Kellerkinder\FancySorter\ClothingSizeSorter;
use Kellerkinder\FancySorter\JeansSizeSorter;
use Kellerkinder\FancySorter\NumericSorter;
use Kellerkinder\FancySorter\AlphanumericSorter;
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
    $sorter = new ChainedSorter([
      new AlphanumericSorter()
    ]);

    $this->assertTrue($sorter->supports($input));
  }

  public function testChainedWithOnlyNumeric()
  {
    $sorter = new ChainedSorter([
      new NumericSorter()
    ]);

    $this->assertFalse($sorter->supports(['32W/34L', '30/32']));
    $this->assertTrue($sorter->supports([4, '3', 5, 1, 2]));
  }

  /**
   * @expectedException RuntimeException
   * @expectedExceptionMessage Kellerkinder\FancySorter\ChainedSorter (containing Kellerkinder\FancySorter\NumericSorter) does not support sorting the following values: "32W\/34L", "30\/32"
   */
  public function testChainedWithOnlyNumericSort()
  {
    $sorter = new ChainedSorter([
      new NumericSorter()
    ]);

    $sorter->sort(['32W/34L', '30/32']);
  }

  public function testChainedWithOnlyJeansSize()
  {
    $sorter = new ChainedSorter([
      new JeansSizeSorter()
    ]);

    $this->assertFalse($sorter->supports(['M','L','S','XL','XS']));
    $this->assertTrue($sorter->supports(['32W/34L', '30/32']));
  }

  public function testChainedWithOnlyClothingSize()
  {
    $sorter = new ChainedSorter([
      new ClothingSizeSorter()
    ]);

    $this->assertFalse($sorter->supports(['Green', 'Blue', 'Red']));
    $this->assertTrue($sorter->supports(['M','L','S','XL','XS']));
  }

  /**
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage Kellerkinder\FancySorter\ChainedSorter does only accept an array of SorterInterface instances
   */
  public function testChainedInvalidInstance()
  {
    new ChainedSorter([
      new \stdClass()
    ]);
  }

  /**
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage Kellerkinder\FancySorter\ChainedSorter does only accept an array of SorterInterface instances
   */
  public function testChainedConstructorString()
  {
    new ChainedSorter([
      'INVALID'
    ]);
  }

  /**
   * @dataProvider valueProvider
   */
  public function testChainedEmptyArray($input)
  {
    $sorter = new ChainedSorter();
    $this->assertTrue($sorter->supports($input));
  }

  /**
   * @dataProvider nestedValueProvider
   */
  public function testNestedChainedEmptyArray($input)
  {
    $sorter = new ChainedSorter(
      [],
      function($value) {
        return $value['optionname'];
      }
    );
    $this->assertTrue($sorter->supports($input));
  }

  public function valueProvider()
  {
    return [
      [
        ['32W/34L', '30/32'],
        ['30/32', '32W/34L'],
        'Kellerkinder\FancySorter\JeansSizeSorter'
      ],
      [
        [96, 52, '128', 50, 54],
        [50, 52, 54, 96, '128'],
        'Kellerkinder\FancySorter\NumericSorter'
      ],
      [
        ['Green', 'Blue', 'Red'],
        ['Blue', 'Green', 'Red'],
        'Kellerkinder\FancySorter\AlphanumericSorter'

      ],
      [
        ['M','L','S','XL','XS'],
        ['XS', 'S', 'M', 'L', 'XL'],
        'Kellerkinder\FancySorter\ClothingSizeSorter'
      ]
    ];
  }

  public function nestedValueProvider()
  {
    return [
      [
        [
          55 => ['optionname' => '27/32'],
          54 => ['optionname' => '26/32'],
          58 => ['optionname' => '26/34']
        ],
        [
          ['optionname' => '26/32'],
          ['optionname' => '26/34'],
          ['optionname' => '27/32']
        ],
        'Kellerkinder\FancySorter\JeansSizeSorter'
      ],
      [
        [
          20 => ['optionname' => '128'],
          10 => ['optionname' => '96'],
          11 => ['optionname' => '52']
        ],
        [
          ['optionname' => '52'],
          ['optionname' => '96'],
          ['optionname' => '128']
        ],
        'Kellerkinder\FancySorter\NumericSorter'
      ],
      [
        [
          20 => ['optionname' => 'Green'],
          10 => ['optionname' => 'Red'],
          11 => ['optionname' => 'Blue']
        ],
        [
          ['optionname' => 'Blue'],
          ['optionname' => 'Green'],
          ['optionname' => 'Red']
        ],
        'Kellerkinder\FancySorter\AlphanumericSorter'

      ],
      [
        [
          20 => ['optionname' => 'M'],
          10 => ['optionname' => 'S'],
          11 => ['optionname' => 'L']
        ],
        [
          ['optionname' => 'S'],
          ['optionname' => 'M'],
          ['optionname' => 'L']
        ],
        'Kellerkinder\FancySorter\ClothingSizeSorter'
      ]
    ];
  }
}