<?php

require_once 'gilded_rose.php';
require_once 'item_builder.php';

// 39 test cases
class GildedRoseTest extends PHPUnit_Framework_TestCase {

    // At the end of each day our system lowers both values for every item
    /** @test */
    function shouldDecreaseSellInOfOrdinaryItem() {
        $initialSellIn = 5;
        $this->create->ordinaryItem()->toSellIn($initialSellIn);

        $this->updateQuality();

        $this->assertThatSellInIs(lessThan($initialSellIn)); // variant (1a), general
        $this->assertThatSellInIs(equalTo($initialSellIn - 1)); // variant (1b), specific
    }

    /** @test */
    function shouldDecreaseQualityOfOrdinaryItem() {
        $this->create->ordinaryItem()->item();

        $this->updateQuality();

        $this->assertThatQualityIs($this->decreasedBy(1)); // variant (2), remove local variable
    }

    // Once the sell by date has passed, Quality degrades twice as fast
    /** @test */
    function shouldDecreaseQualityOfExpiredOrdinaryItemTwiceAsFast() {
        $initialQuality = 13;
        $this->create->expired()->ordinaryItem()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality - 2));
    }

    // boundary
    /** @test */
    function shouldDecreaseQualityOfOrdinaryItemOnLastDayStillByOne() {
        $initialQuality = 9;
        $this->create->almostExpired()->ordinaryItem()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality - 1));
    }

    // boundary
    /** @test */
    function shouldDecreaseQualityOfOrdinaryItemOnSellDateAlreadyByTwo() {
        $initialQuality = 8;
        $this->create->justExpired()->ordinaryItem()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality - 2));
    }

    // The Quality of an item is never negative
    /** @test */
    function shouldNotDecreaseQualityOfOrdinaryItemBelowZero() {
        $this->create->ordinaryItem()->ofNoQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

    /** @test */
    function shouldNotDecreaseQualityOfExpiredOrdinaryItemWithNoQualityBelowZero() {
        $this->create->expired()->ordinaryItem()->ofNoQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

    // boundary
    /** @test */
    function shouldNotDecreaseQualityOfExpiredOrdinaryItemWithOneQualityBelowZero() {
        $this->create->expired()->ordinaryItem()->ofQuality(1);

        $this->updateQuality();

        $this->assertThatQualityIs(not($this->negative()));
    }

    // boundary
    /** @test */
    function shouldDecreaseQualityOfOrdinaryItemDownToZero() {
        $this->create->ordinaryItem()->ofQuality(1);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo(0));
    }

    // boundary
    /** @test */
    function shouldDecreaseQualityOfExpiredOrdinaryItemDownToZero() {
        $this->create->expired()->ordinaryItem()->ofQuality(2);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo(0));
    }

    // "Aged Brie" actually increases in Quality the older it gets
    /** @test */
    function shouldIncreaseQualityOfAgedBrie() {
        $initialQuality = 17;
        $this->create->agedBrie()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(greaterThan($initialQuality));
    }

    /** @test */
    function shouldIncreaseQualityOfExpiredAgedBrieTwiceAsFast() {
        $initialQuality = 21;
        $this->create->expired()->agedBrie()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality + 2));
    }

    // boundary
    /** @test */
    function shouldIncreaseQualityOfAgedBrieOnLastDayStillByOne() {
        $initialQuality = 19;
        $this->create->almostExpired()->agedBrie()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality + 1));
    }

    // boundary
    /** @test */
    function shouldIncreaseQualityOfAgedBrieOnSellDateAlreadyByTwo() {
        $initialQuality = 18;
        $this->create->justExpired()->agedBrie()->ofQuality($initialQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($initialQuality + 2));
    }

    // The Quality of an item is never more than 50
    /** @test */
    function shouldNotIncreaseQualityOfAgedBrieAboveMax() {
        $this->create->agedBrie()->ofMaxQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    /** @test */
    function shouldNotIncreaseQualityOfExpiredAgedBrieWithMaxQualityAboveMax() {
        $this->create->expired()->agedBrie()->ofMaxQuality();

        $this->updateQuality();

        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    // boundary
    /** @test */
    function shouldNotIncreaseQualityOfExpiredAgedBriedWithAlmostMaxQualityAboveMax() {
        $this->create->expired()->agedBrie()->ofQuality(49);

        $this->updateQuality();

        $this->assertThatQualityIs(not(greaterThan(50))); // variant (1a)
        $this->assertThatQualityIs($this->maximal()); // variant (1b)
    }

    // boundary
    /** @test */
    function shouldIncreaseQualityOfAgedBrieUptoMax() {
        $this->create->agedBrie()->ofQuality(49);

        $this->updateQuality();

        $this->assertThatQualityIs($this->maximal());
    }

    // boundary
    /** @test */
    function shouldIncreaseQualityOfExpiredAgedBrieUptoMax() {
        $this->create->expired()->agedBrie()->ofQuality(48);

        $this->updateQuality();

        $this->assertThatQualityIs($this->maximal());
    }

    /** @test */
    function shouldNotIncreaseQualityOfBackstagePassAboveMax() {
        $this->create->backstagePass()->withSellIn(15)->ofMaxQuality();
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    /** @test */
    function shouldNotIncreaseQualityOfBackstagePass10DaysBeforeConcertAboveMax() {
        $this->create->backstagePass()->withSellIn(10)->ofMaxQuality();
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    /** @test */
    function shouldNotIncreaseQualityOfBackstagePassShortlyBeforeConcertAboveMax() {
        $this->create->backstagePass()->withSellIn(4)->ofMaxQuality();
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    // boundary
    /** @test */
    function shouldNotIncreaseQualityOfBackstagePassWithAlmostMaxQuality10DaysBeforeConcertAboveMax() {
        $this->create->backstagePass()->withSellIn(10)->ofQuality(49);
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    // boundary
    /** @test */
    function shouldNotIncreaseQualityOfBackstagePassWithAlmostMaxQualityShortlyBeforeConcertAboveMax() {
        $this->create->backstagePass()->withSellIn(2)->ofQuality(49);
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    // boundary
    /** @test */
    function shouldNotIncreaseQualityOfBackstagePassWithNearMaxQualityShortlyBeforeConcertAboveMax() {
        $this->create->backstagePass()->withSellIn(1)->ofQuality(48);
        $this->updateQuality();
        $this->assertThatQualityIs(not(greaterThan(50)));
    }

    // boundary
    /** @test */
    function shouldIncreaseQualityOfBackstagePassUptoMax() {
        $this->create->backstagePass()->withSellIn(20)->ofQuality(49);
        $this->updateQuality();
        $this->assertThatQualityIs($this->maximal());
    }

    // boundary
    /** @test */
    function shouldIncreaseQualityOfBackstagePass10DaysBeforeConcertUptoMax() {
        $this->create->backstagePass()->withSellIn(10)->ofQuality(48);
        $this->updateQuality();
        $this->assertThatQualityIs($this->maximal());
    }

    // boundary
    /** @test */
    function shouldIncreaseQualityOfBackstagePassShortlyBeforeConcertUptoMax() {
        $this->create->backstagePass()->withSellIn(5)->ofQuality(47);
        $this->updateQuality();
        $this->assertThatQualityIs($this->maximal());
    }

    // "Sulfuras", being a legendary item, never has to be sold or decreases in Quality
    /** @test */
    function shouldNotDecreaseSellInOfSulfuras() {
        $initialSellIn = 5;
        $this->create->sulfuras()->toSellIn($initialSellIn);

        $this->updateQuality();

        $this->assertThatSellInIs(equalTo($initialSellIn));
    }

    /** @test */
    function shouldNotDecreaseQualityOfSulfuras() {
        $this->create->sulfuras()->item();

        $this->updateQuality();

        $this->assertThatQualityIs($this->unchanged());
    }

    /** @test */
    function shouldNotDecreaseQualityOfExpiredSulfuras() {
        $legendaryQuality = 80;
        $this->create->expired()->sulfuras()->ofQuality($legendaryQuality);

        $this->updateQuality();

        $this->assertThatQualityIs(equalTo($legendaryQuality));
    }

    // "Backstage passes" increases in Quality as it's SellIn value approaches;
    // Quality increases by 2 when there are 10 days or less and
    // by 3 when there are 5 days or less but Quality drops to 0 after the concert
    /** @test */
    function shouldIncreaseQualityOfBackstagePass() {
        $this->create->backstagePass()->toSellIn(20);
        $this->updateQuality();
        $this->assertThatQualityIs($this->increasedBy(1));
    }

    // boundary
    /** @test */
    function shouldIncreaseQualityOfBackstagePassStillByOne11DaysBeforeConcert() {
        $this->create->backstagePass()->toSellIn(11);
        $this->updateQuality();
        $this->assertThatQualityIs($this->increasedBy(1));
    }

    /** @test */
    function shouldIncreaseQualityOfBackstagePassByTwo10DaysBeforeConcert() {
        $this->create->backstagePass()->toSellIn(10);
        $this->updateQuality();
        $this->assertThatQualityIs($this->increasedBy(2));
    }

    // boundary
    /** @test */
    function shouldIncreaseQualityOfBackstagePassStillByTwo6DaysBeforeConcert() {
        $this->create->backstagePass()->toSellIn(6);
        $this->updateQuality();
        $this->assertThatQualityIs($this->increasedBy(2));
    }

    /** @test */
    function shouldIncreaseQualityOfBackstagePassByThreeShortlyBeforeConcert() {
        $this->create->backstagePass()->toSellIn(5);
        $this->updateQuality();
        $this->assertThatQualityIs($this->increasedBy(3));
    }

    // boundary
    /** @test */
    function shouldIncreaseQualityOfBackstagePassStillByThreeADayBeforeConcert() {
        $this->create->backstagePass()->toSellIn(1);
        $this->updateQuality();
        $this->assertThatQualityIs($this->increasedBy(3));
    }

    /** @test */
    function shouldDropQualityOfExpiredBackstagePass() {
        $this->create->expired()->backstagePass()->item();
        $this->updateQuality();
        $this->assertThatQualityIs(equalTo(0));
    }

    // boundary
    /** @test */
    function shouldDropQualityOfBackstagePassOnDayOfConcert() {
        $this->create->justExpired()->backstagePass()->item();
        $this->updateQuality();
        $this->assertThatQualityIs(equalTo(0));
    }

    // --- infrastructure

    /** @var Item */
    private $item;
     
    /** @var Item */
    protected $create;

    function __construct() {
        $self = $this;
        $this->create = new ItemBuilder(
                function(Item $item) use ($self) {
                    $self->item = $item;
                }
        );
    }

    protected function updateQuality() {
        $items = array($this->item);
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
    }

    protected function assertThatQualityIs($matcher) {
        assertThat($this->item->quality, $matcher);
    }

    protected function decreasedBy($number) {
        return $this->increasedBy(-$number);
    }

    protected function negative() {
        return lessThan(0);
    }

    private function unchanged() {
        return equalTo($this->create->initialQuality());
    }

    private function maximal() {
        return equalTo(50);
    }

    private function increasedBy($number) {
        return equalTo($this->create->initialQuality() + $number);
    }

    private function assertThatSellInIs($matcher) {
        assertThat($this->item->sell_in, $matcher);
    }

}
