<?php

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

abstract class GildedRoseItemTestCase extends TestCase
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

    protected function unchanged()
    {
        return equalTo($this->itemBuilder->initialQuality());
    }

    protected function maximal()
    {
        return equalTo(50);
    }

    protected function increasedBy($number)
    {
        return equalTo($this->itemBuilder->initialQuality() + $number);
    }

    protected function assertThatSellInIs($matcher)
    {
        assertThat($this->item->sellIn, $matcher);
    }
}
