<?php

namespace Test;

use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    function test_it_should_have_a_sell_in_value()
    {
        $this->assertClassHasAttribute("sell_in", Item::class);
    }

    function test_it_should_have_a_quality_value()
    {
        $this->assertClassHasAttribute("quality", Item::class);
    }

    function test_it_should_display_with_name_and_values()
    {
        $itemBuilder = new ItemBuilder(
            function ($item) {
            }
        );

        $item = $itemBuilder->agedBrie()->withSellIn(3)->ofQuality(7);

        $this->assertEquals("Aged Brie, 3, 7", (string)$item);
    }
}
