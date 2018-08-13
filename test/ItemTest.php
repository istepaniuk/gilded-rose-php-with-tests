<?php

namespace Test;

use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase {

    /**
     * @test
     */
    function shouldHaveASellInValue() {
        $this->assertClassHasAttribute("sell_in", Item::class);
    }

    /**
     * @test
     */
    function shouldHaveAQualityValue() {
        $this->assertClassHasAttribute("quality", Item::class);
    }

    /**
     * @test
     */
    function shouldDisplayWithNameAndValues() {
        $itemBuilder = new ItemBuilder(
            function($item){
            }
        );
        $item = $itemBuilder->agedBrie()->withSellIn(3)->ofQuality(7);
        assertThat($item->__toString(), is("Aged Brie, 3, 7"));
    }
}
