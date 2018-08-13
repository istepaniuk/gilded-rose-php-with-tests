<?php

namespace Tests;

final class OrdinaryItemTest extends GildedRoseItemTestCase
{
    // At the end of each day our system lowers both values for every item
    public function test_it_should_decrease_sell_in_of_ordinary_item()
    {
        $initialSellIn = 5;
        $this->itemBuilder->ordinaryItem()->toSellIn($initialSellIn);

        $this->updateQuality();

        $this->assertThatSellInIs(lessThan($initialSellIn)); // variant (1a), general
        $this->assertThatSellInIs(equalTo($initialSellIn - 1)); // variant (1b), specific
    }

    public function test_it_should_decrease_quality_of_ordinary_item()
    {
        $this->itemBuilder->ordinaryItem()->item();

        $this->updateQuality();

        $this->assertThatQualityIs($this->decreasedBy(1)); // variant (2), remove local variable
    }

    // Once the sell by date has passed, Quality degrades twice as fast
    public function test_it_should_decrease_quality_of_expired_ordinary_item_twice_as_fast()
    {
        $initialQuality = 13;
        $this->itemBuilder->expired()->ordinaryItem()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality - 2));
    }

    // boundary
    public function test_it_should_decrease_quality_of_ordinary_item_on_last_day_still_by_one()
    {
        $initialQuality = 9;
        $this->itemBuilder->almostExpired()->ordinaryItem()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality - 1));
    }

    // boundary
    public function test_it_should_decrease_quality_of_ordinary_item_on_sell_date_already_by_two()
    {
        $initialQuality = 8;
        $this->itemBuilder->justExpired()->ordinaryItem()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality - 2));
    }

    // The Quality of an item is never negative
    public function test_it_should_not_decrease_quality_of_ordinary_item_below_zero()
    {
        $this->itemBuilder->ordinaryItem()->ofNoQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(logicalNot($this->negative()));
    }

    public function test_it_should_not_decrease_quality_of_expired_ordinary_item_with_no_quality_below_zero()
    {
        $this->itemBuilder->expired()->ordinaryItem()->ofNoQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(logicalNot($this->negative()));
    }

    // boundary
    public function test_it_should_not_decrease_quality_of_expired_ordinary_item_withone_quality_below_zero()
    {
        $this->itemBuilder->expired()->ordinaryItem()->ofQuality(1);

        $this->updateQuality();

        $this->assertThatQualityIs(logicalNot($this->negative()));
    }

    // boundary
    public function test_it_should_decrease_quality_of_ordinary_item_down_to_zero()
    {
        $this->itemBuilder->ordinaryItem()->ofQuality(1);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo(0));
    }

    // boundary
    public function test_it_should_decrease_quality_of_expired_ordinary_item_down_to_zero()
    {
        $this->itemBuilder->expired()->ordinaryItem()->ofQuality(2);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo(0));
    }
}
