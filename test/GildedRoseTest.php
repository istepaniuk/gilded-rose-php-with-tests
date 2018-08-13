<?php

namespace Test;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    private $item;

    /** @var ItemBuilder */
    protected $itemBuilder;

    protected function setUp()
    {
        $self = $this;
        $this->itemBuilder = new ItemBuilder(
            function (Item $item) use ($self) {
                $self->item = $item;
            }
        );
    }

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

        $this->assertThatQualityIs(not($this->negative()));
    }

    public function test_it_should_not_decrease_quality_of_expired_ordinary_item_with_no_quality_below_zero()
    {
        $this->itemBuilder->expired()->ordinaryItem()->ofNoQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

    // boundary
    public function test_it_should_not_decrease_quality_of_expired_ordinary_item_withone_quality_below_zero()
    {
        $this->itemBuilder->expired()->ordinaryItem()->ofQuality(1);

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
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

    public function test_it_should_not_increase_quality_of_backstage_pass_above_max()
    {
        $this->itemBuilder->backstagePass()->withSellIn(15)->ofMaxQuality();
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    public function test_it_should_not_increase_quality_of_backstage_pass_10_days_before_concert_above_max()
    {
        $this->itemBuilder->backstagePass()->withSellIn(10)->ofMaxQuality();
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    public function test_it_should_not_increase_quality_of_backstage_pass_shortly_before_concert_above_max()
    {
        $this->itemBuilder->backstagePass()->withSellIn(4)->ofMaxQuality();
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    // boundary
    public function test_it_should_not_increase_quality_of_backstage_pass_with_almost_max_quality_10_days_before_concert_above_max(
    )
    {
        $this->itemBuilder->backstagePass()->withSellIn(10)->ofQuality(49);
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    // boundary
    public function test_it_should_not_increase_quality_of_backstage_pass_with_almost_max_quality_shortly_before_concert_above_max(
    )
    {
        $this->itemBuilder->backstagePass()->withSellIn(2)->ofQuality(49);
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    // boundary
    public function test_it_should_not_increase_quality_of_backstage_pass_with_near_max_quality_shortly_before_concert_above_max(
    )
    {
        $this->itemBuilder->backstagePass()->withSellIn(1)->ofQuality(48);
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
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

    protected function updateQuality()
    {
        $gildedRose = new GildedRose([$this->item]);
        $gildedRose->updateQuality();
    }

    protected function assertThatQualityIs($matcher)
    {
        assertThat($this->item->quality, $matcher);
    }

    protected function decreasedBy($number)
    {
        return $this->increasedBy(-$number);
    }

    protected function negative()
    {
        return lessThan(0);
    }

    private function unchanged()
    {
        return equalTo($this->itemBuilder->initialQuality());
    }

    private function maximal()
    {
        return equalTo(50);
    }

    private function increasedBy($number)
    {
        return equalTo($this->itemBuilder->initialQuality() + $number);
    }

    private function assertThatSellInIs($matcher)
    {
        assertThat($this->item->sell_in, $matcher);
    }

}
