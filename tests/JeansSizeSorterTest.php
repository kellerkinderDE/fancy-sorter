<?php

namespace Kellerkinder\FancySorter\Tests;

use Kellerkinder\FancySorter\JeansSizeSorter;
use PHPUnit_Framework_TestCase;

class JeansSizeSorterTest extends PHPUnit_Framework_TestCase
{
  public function testBasicSort()
  {
    $sorter = new JeansSizeSorter();

    $this->assertSame(
      ['30/30', '30/32', '32/30', '32/32', '32/34'],
      $sorter->sort(['30/32', '32/34', '32/30', '30/30', '32/32'])
    );
  }

  public function testMixedCaseSort()
  {
    $sorter = new JeansSizeSorter();

    $this->assertSame(
      ['30W/30L', '30/32', '32/ 30', '32W / 32L', '32 / 34'],
      $sorter->sort(['30/32', '32 / 34', '32/ 30', '30W/30L', '32W / 32L'])
    );
  }

  public function validInputProvider()
  {
    return [
      [['32/34', '32W/34L', '30 / 32', '30/ 32', '30 /32', '30W / 32L']],
    ];
  }

  /**
   * @dataProvider validInputProvider
   */
  public function testDoesSupport($input)
  {
    $sorter = new JeansSizeSorter();
    $this->assertTrue($sorter->supports($input));
  }

  public function invalidInputProvider()
  {
    return [
      [
        ['M', 'L', 'S', 'XS', '3XL'],
        'FancySorter\JeansSizeSorter does not support sorting the following values: "M", "L", "S", "XS", "3XL"'
      ],
      [
        [1, 2, 3, 4, 5],
        'FancySorter\JeansSizeSorter does not support sorting the following values: 1, 2, 3, 4, 5'
      ],
      [
        ['Green', 'Blue', 'Red'],
        'FancySorter\JeansSizeSorter does not support sorting the following values: "Green", "Blue", "Red"'
      ]
    ];
  }

  /**
   * @dataProvider invalidInputProvider
   */
  public function testDoesNotSupport($input)
  {
    $sorter = new JeansSizeSorter();
    $this->assertFalse($sorter->supports($input));
  }

  /**
   * @dataProvider invalidInputProvider
   */
  public function testInvalidSort($input, $expectedExceptionMessage)
  {
    $this->setExpectedException('InvalidArgumentException', $expectedExceptionMessage);

    $sorter = new JeansSizeSorter();
    $sorter->sort($input);
  }

  public function testArraySort()
  {
    $sorter = new JeansSizeSorter(
      function($value) {
        return $value['optionname'];
      }
    );

    $this->assertSame(
      [
        ['optionname' => '26/32'],
        ['optionname' => '26/34'],
        ['optionname' => '27/32']
      ],
      $sorter->sort(
        [
          55 => ['optionname' => '27/32'],
          54 => ['optionname' => '26/32'],
          58 => ['optionname' => '26/34']
        ]
      )
    );
  }
}
