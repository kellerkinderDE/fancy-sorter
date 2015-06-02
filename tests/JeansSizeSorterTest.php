<?php

namespace FancySorter\Tests;

use FancySorter\JeansSizeSorter;
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
      [['M', 'L', 'S', 'XS', '3XL']], // ClothingSize
      [[1, 2, 3, 4, 5]], // Numeric
      [['Green', 'Blue', 'Red']] // Alphanumeric
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
}
