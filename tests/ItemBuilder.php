<?php

namespace Tests;

use GildedRose\Item;

final class ItemBuilder
{
    const FRESH = 5;
    const NO_QUALITY = 0;
    const MAX_QUALITY = 50;

    private $itemSetter;
    private $sellIn;
    private $quality;
    private $name;

    public function __construct(callable $itemSetter)
    {
        $this->itemSetter = $itemSetter;
        $this->sellIn = self::FRESH;
        $this->quality = 10;
    }

    public function ordinaryItem(): self
    {
        return $this->named("any ordinary item");
    }

    public function agedBrie(): self
    {
        return $this->named("Aged Brie");
    }

    public function sulfuras(): self
    {
        return $this->named("Sulfuras, Hand of Ragnaros");
    }

    public function backstagePass(): self
    {
        return $this->named("Backstage passes to a TAFKAL80ETC concert");
    }

    public function conjuredItem(): self
    {
        return $this->named("Conjured Mana Cake");
    }

    private function named(string $itemName): self
    {
        $this->name = $itemName;

        return $this;
    }

    public function almostExpired(): self
    {
        return $this->withSellIn(1);
    }

    public function justExpired(): self
    {
        return $this->withSellIn(0);
    }

    public function expired(): self
    {
        return $this->withSellIn(-3);
    }

    public function toSellIn($days): Item
    {
        return $this->withSellIn($days)->item();
    }

    public function withSellIn($days): self
    {
        $this->sellIn = $days;

        return $this;
    }

    public function ofQuality($number): Item
    {
        return $this->withQuality($number)->item();
    }

    public function ofNoQuality(): Item
    {
        return $this->withQuality(self::NO_QUALITY)->item();
    }

    public function ofMaxQuality(): Item
    {
        return $this->withQuality(self::MAX_QUALITY)->item();
    }

    private function withQuality($number): ItemBuilder
    {
        $this->quality = $number;

        return $this;
    }

    public function item(): Item
    {
        return $this->set($this->build());
    }

    private function build(): Item
    {
        return new Item($this->name, $this->sellIn, $this->quality);
    }

    private function set(Item $item): Item
    {
        call_user_func($this->itemSetter, $item);

        return $item;
    }

    public function initialQuality(): int
    {
        return $this->quality;
    }

}
