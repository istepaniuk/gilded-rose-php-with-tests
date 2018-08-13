<?php

namespace Tests;

final class SulfurasItemTest extends GildedRoseItemTestCase
{
    // "Sulfuras", being a legendary item, never has to be sold or decreases in Quality
    public function test_it_should_not_decrease_sell_in_of_sulfuras()
    {
        $initialSellIn = 5;
        $this->itemBuilder->sulfuras()->toSellIn($initialSellIn);

        $this->updateQuality();

        $this->assertThatSellInIs(equalTo($initialSellIn));
    }

    public function test_it_should_not_decrease_quality_of_sulfuras()
    {
        $this->itemBuilder->sulfuras()->item();

        $this->updateQuality();

        $this->assertThatQualityIs($this->unchanged());
    }

    public function test_it_should_not_decrease_quality_of_expired_sulfuras()
    {
        $legendaryQuality = 80;
        $this->itemBuilder->expired()->sulfuras()->ofQuality($legendaryQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($legendaryQuality));
    }
}
