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
}
