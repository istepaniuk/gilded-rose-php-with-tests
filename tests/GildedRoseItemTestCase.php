<?php

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\Constraint\Constraint;
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

    protected function updateQuality(): void
    {
        $gildedRose = new GildedRose([$this->item]);
        $gildedRose->updateQuality();
    }

    protected function assertThatQualityIs(Constraint $matcher): void
    {
        assertThat($this->item->quality, $matcher);
    }

    protected function assertThatQualityIsNot(Constraint $matcher): void
    {
        $this->assertThatQualityIs(logicalNot($matcher));
    }

    protected function decreasedBy(int $number): Constraint
    {
        return $this->increasedBy(-$number);
    }

    protected function negative(): Constraint
    {
        return lessThan(0);
    }

    protected function unchanged(): Constraint
    {
        return equalTo($this->itemBuilder->initialQuality());
    }

    protected function maximal(): Constraint
    {
        return equalTo(50);
    }

    protected function increasedBy(int $number): Constraint
    {
        return equalTo($this->itemBuilder->initialQuality() + $number);
    }

    protected function assertThatSellInIs(Constraint $matcher): void
    {
        assertThat($this->item->sellIn, $matcher);
    }
}
