<?php

namespace Test;

class GildedRoseNewFeatureTest extends GildedRoseTest
{

    // "Conjured" items degrade in Quality twice as fast as normal items
    public function test_it_should_decrease_quality_of_conjured_item_Twice_As_Fast()
    {
        $this->create->conjuredItem()->item();

        $this->updateQuality();

        $this->markTestIncomplete(
            'Feature has not been implemented yet.'
        );

        $this->assertThatQualityIs($this->decreasedBy(2));
    }

    // Once the sell by date has passed, Quality degrades twice as fast
    public function test_it_should_decrease_quality_of_expired_conjured_item_twice_as_fast()
    {
        $this->create->expired()->conjuredItem()->item();

        $this->updateQuality();

        $this->markTestIncomplete(
            'Feature has not been implemented yet.'
        );

        $this->assertThatQualityIs($this->decreasedBy(2 * 2));
    }

    // boundary (The Quality of an item is never negative)
    public function test_it_should_not_decrease_quality_of_conjured_item_with_one_quality_below_zero()
    {
        $this->create->conjuredItem()->ofQuality(1);

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

    // boundary
    public function test_it_should_not_decrease_quality_of_expired_conjured_item_with_one_quality_below_zero()
    {
        $this->create->expired()->conjuredItem()->ofQuality(1);

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

    // boundary
    public function test_it_should_not_decrease_quality_of_expired_conjured_item_with_two_quality_below_zero()
    {
        $this->create->expired()->conjuredItem()->ofQuality(2);

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

    // boundary
    public function test_it_should_not_decrease_quality_of_expired_conjured_item_with_three_quality_below_zero()
    {
        $this->create->expired()->conjuredItem()->ofQuality(3);

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

}
