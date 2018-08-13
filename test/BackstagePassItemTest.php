<?php

namespace Test;

final class BackstagePassItemTest extends GildedRoseItemTestCase
{
    // "Backstage passes" increases in Quality as it's SellIn value approaches;
    // Quality increases by 2 when there are 10 days or less and
    // by 3 when there are 5 days or less but Quality drops to 0 after the concert
    public function test_it_should_increase_quality_of_backstage_pass()
    {
        $this->itemBuilder->backstagePass()->toSellIn(20);

        $this->updateQuality();

        $this->assertThatQualityIs($this->increasedBy(1));
    }

    // boundary
    public function test_it_should_increase_quality_of_backstage_pass_still_by_one_11_days_before_concert()
    {
        $this->itemBuilder->backstagePass()->toSellIn(11);

        $this->updateQuality();

        $this->assertThatQualityIs($this->increasedBy(1));
    }

    public function test_it_should_increase_quality_of_backstage_pass_by_two_10_days_before_concert()
    {
        $this->itemBuilder->backstagePass()->toSellIn(10);

        $this->updateQuality();

        $this->assertThatQualityIs($this->increasedBy(2));
    }

    // boundary
    public function test_it_should_increase_quality_of_backstage_pass_still_by_two_6_days_before_concert()
    {
        $this->itemBuilder->backstagePass()->toSellIn(6);

        $this->updateQuality();

        $this->assertThatQualityIs($this->increasedBy(2));
    }

    public function test_it_should_increase_quality_of_backstage_pass_by_three_shortly_before_concert()
    {
        $this->itemBuilder->backstagePass()->toSellIn(5);

        $this->updateQuality();

        $this->assertThatQualityIs($this->increasedBy(3));
    }

    // boundary
    public function test_it_should_increase_quality_of_backstage_pass_still_by_three_a_day_before_concert()
    {
        $this->itemBuilder->backstagePass()->toSellIn(1);

        $this->updateQuality();

        $this->assertThatQualityIs($this->increasedBy(3));
    }

    public function test_it_should_drop_quality_of_expired_backstage_pass()
    {
        $this->itemBuilder->expired()->backstagePass()->item();

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo(0));
    }

    // boundary
    public function test_it_should_drop_quality_of_backstage_pass_on_day_of_concert()
    {
        $this->itemBuilder->justExpired()->backstagePass()->item();

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo(0));
    }

    public function test_it_should_not_increase_quality_of_backstage_pass_above_max()
    {
        $this->itemBuilder->backstagePass()->withSellIn(15)->ofMaxQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(logicalNot(greaterThan(50)));
    }

    public function test_it_should_not_increase_quality_of_backstage_pass_10_days_before_concert_above_max()
    {
        $this->itemBuilder->backstagePass()->withSellIn(10)->ofMaxQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(logicalNot(greaterThan(50)));
    }

    public function test_it_should_not_increase_quality_of_backstage_pass_shortly_before_concert_above_max()
    {
        $this->itemBuilder->backstagePass()->withSellIn(4)->ofMaxQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(logicalNot(greaterThan(50)));
    }

    // boundary
    public function test_it_should_not_increase_quality_of_backstage_pass_with_almost_max_quality_10_days_before_concert_above_max(
    )
    {
        $this->itemBuilder->backstagePass()->withSellIn(10)->ofQuality(49);

        $this->updateQuality();

        $this->assertThatQualityIs(logicalNot(greaterThan(50)));
    }

    // boundary
    public function test_it_should_not_increase_quality_of_backstage_pass_with_almost_max_quality_shortly_before_concert_above_max(
    )
    {
        $this->itemBuilder->backstagePass()->withSellIn(2)->ofQuality(49);

        $this->updateQuality();

        $this->assertThatQualityIs(logicalNot(greaterThan(50)));
    }

    // boundary
    public function test_it_should_not_increase_quality_of_backstage_pass_with_near_max_quality_shortly_before_concert_above_max(
    )
    {
        $this->itemBuilder->backstagePass()->withSellIn(1)->ofQuality(48);

        $this->updateQuality();

        $this->assertThatQualityIs(logicalNot(greaterThan(50)));
    }

    // boundary
    public function test_it_should_increase_quality_of_backstage_pass_up_to_max()
    {
        $this->itemBuilder->backstagePass()->withSellIn(20)->ofQuality(49);

        $this->updateQuality();

        $this->assertThatQualityIs($this->maximal());
    }

    // boundary
    public function test_it_should_increase_quality_of_backstage_pass_10_days_before_concert_up_to_max()
    {
        $this->itemBuilder->backstagePass()->withSellIn(10)->ofQuality(48);

        $this->updateQuality();

        $this->assertThatQualityIs($this->maximal());
    }

    // boundary
    public function test_it_should_increase_quality_of_backstage_pass_shortly_before_concert_up_to_max()
    {
        $this->itemBuilder->backstagePass()->withSellIn(5)->ofQuality(47);

        $this->updateQuality();

        $this->assertThatQualityIs($this->maximal());
    }
}
