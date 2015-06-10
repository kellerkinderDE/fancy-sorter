<?php

namespace Kellerkinder\FancySorter\Tests;

use Kellerkinder\FancySorter\AlphanumericSorter;
use PHPUnit_Framework_TestCase;

class AlphanumericSorterTest extends PHPUnit_Framework_TestCase
{
  public function testSort()
  {
    $sorter = new AlphanumericSorter();

    $this->assertSame(
      ['Blue', 'Green', 'Red'],
      $sorter->sort(['Green', 'Red', 'Blue'])
    );
  }

  /**
   * AlphanumericSorter is supposed to be able to support _everything_
   * since the plan is, that if someone else is better suited to sort
   * it, he/she should speak up and take it.
   */
  public function testSupports()
  {
    $sorter = new AlphanumericSorter();
    $this->assertTrue($sorter->supports(['whatever']));
  }

  public function testArraySort()
  {
    $sorter = new AlphanumericSorter(
      function($value) {
        return $value['optionname'];
      }
    );

    $this->assertSame(
      [
        ['optionname' => 'Blue'],
        ['optionname' => 'Green'],
        ['optionname' => 'Red']
      ],
      $sorter->sort(
        [
          20 => ['optionname' => 'Green'],
          10 => ['optionname' => 'Red'],
          11 => ['optionname' => 'Blue']
        ]
      )
    );
  }
}
