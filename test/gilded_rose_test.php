<?php

require_once 'gilded_rose.php';
require_once 'item_builder.php';

// 39 test cases
class GildedRoseTest extends PHPUnit_Framework_TestCase {

    // At the end of each day our system lowers both values for every item
    /**
     * @test
     */
    function shouldDecreaseSellInOfOrdinaryItem() {
        $initialSellIn = 5;
        $this->create->ordinaryItem()->toSellIn($initialSellIn);

        $this->updateQuality();

        $this->assertThatSellInIs(lessThan($initialSellIn)); // variant (1a), general
        $this->assertThatSellInIs(equalTo($initialSellIn - 1)); // variant (1b), specific
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
        return increasedBy(-$number);
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
