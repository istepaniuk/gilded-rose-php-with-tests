<?php

require_once 'gilded_rose_test.php';

// 39 test cases + 6 new test cases = 45 test cases
class GildedRoseNewFeatureTest extends GildedRoseTest {

    // "Conjured" items degrade in Quality twice as fast as normal items
    /** @test */
    function shouldDecreaseQualityOfConjuredItemTwiceAsFast() {
        $this->create->conjuredItem()->item();

        $this->updateQuality();

        $this->markTestIncomplete(
                'Feature has not been implemented yet.'
        );

        $this->assertThatQualityIs($this->decreasedBy(2));
    }

    // Once the sell by date has passed, Quality degrades twice as fast
    /** @test */
    function shouldDecreaseQualityOfExpiredConjuredItemTwiceAsFast() {
        $this->create->expired()->conjuredItem()->item();

        $this->updateQuality();

        $this->markTestIncomplete(
                'Feature has not been implemented yet.'
        );
        
        $this->assertThatQualityIs($this->decreasedBy(2 * 2));
    }

    // boundary (The Quality of an item is never negative)
    /** @test */
    function shouldNotDecreaseQualityOfConjuredItemWithOneQualityBelowZero() {
        $this->create->conjuredItem()->ofQuality(1);

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

    // boundary
    /** @test */
    function shouldNotDecreaseQualityOfExpiredConjuredItemWithOneQualityBelowZero() {
        $this->create->expired()->conjuredItem()->ofQuality(1);

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

    // boundary
    /** @test */
    function shouldNotDecreaseQualityOfExpiredConjuredItemWithTwoQualityBelowZero() {
        $this->create->expired()->conjuredItem()->ofQuality(2);

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

    // boundary
    /** @test */
    function shouldNotDecreaseQualityOfExpiredConjuredItemWithThreeQualityBelowZero() {
        $this->create->expired()->conjuredItem()->ofQuality(3);

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

}
