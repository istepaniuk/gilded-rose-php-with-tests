<?php

namespace Test;

final class AgedBrieItemTest extends GildedRoseItemTestCase
{
    // "Aged Brie" actually increases in Quality the older it gets
    public function test_it_should_increase_quality_of_aged_brie()
    {
        $initialQuality = 17;
        $this->itemBuilder->agedBrie()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(greaterThan($initialQuality));
    }

    public function test_it_should_increase_quality_of_expired_aged_brie_twice_as_fast()
    {
        $initialQuality = 21;
        $this->itemBuilder->expired()->agedBrie()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality + 2));
    }

    // boundary
    public function test_it_should_increase_quality_of_aged_brie_on_last_day_still_by_one()
    {
        $initialQuality = 19;
        $this->itemBuilder->almostExpired()->agedBrie()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality + 1));
    }

    // boundary
    public function test_it_should_increase_quality_of_aged_brie_on_sell_date_already_by_two()
    {
        $initialQuality = 18;
        $this->itemBuilder->justExpired()->agedBrie()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality + 2));
    }

    // The Quality of an item is never more than 50
    public function test_it_should_not_increase_quality_of_aged_brie_above_max()
    {
        $this->itemBuilder->agedBrie()->ofMaxQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    public function test_it_should_not_increase_quality_of_expired_aged_brie_with_max_quality_above_max()
    {
        $this->itemBuilder->expired()->agedBrie()->ofMaxQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    // boundary
    public function test_it_should_not_increase_quality_of_expired_aged_brie_with_almost_max_quality_above_max()
    {
        $this->itemBuilder->expired()->agedBrie()->ofQuality(49);

        $this->updateQuality();

        $this->assertThatQualityIs(not(greaterThan(50))); // variant (1a)
        $this->assertThatQualityIs($this->maximal()); // variant (1b)
    }

    // boundary
    public function test_it_should_increase_quality_of_aged_brie_up_to_max()
    {
        $this->itemBuilder->agedBrie()->ofQuality(49);

        $this->updateQuality();

        $this->assertThatQualityIs($this->maximal());
    }

    // boundary
    public function test_it_should_increase_quality_of_expired_aged_brie_up_to_max()
    {
        $this->itemBuilder->expired()->agedBrie()->ofQuality(48);

        $this->updateQuality();

        $this->assertThatQualityIs($this->maximal());
    }
}
