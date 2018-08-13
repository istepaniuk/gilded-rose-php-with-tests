<?php

namespace Test;

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

    public function __construct($itemSetter)
    {
        $this->itemSetter = $itemSetter;
        $this->sellIn = self::FRESH;
        $this->quality = 10;
    }

    public function ordinaryItem()
    {
        return $this->named("any ordinary item");
    }

    public function agedBrie()
    {
        return $this->named("Aged Brie");
    }

    public function sulfuras()
    {
        return $this->named("Sulfuras, Hand of Ragnaros");
    }

    public function backstagePass()
    {
        return $this->named("Backstage passes to a TAFKAL80ETC concert");
    }

    public function conjuredItem()
    {
        return $this->named("Conjured Mana Cake");
    }

    private function named(string $itemName)
    {
        $this->name = $itemName;

        return $this;
    }

    public function almostExpired()
    {
        return $this->withSellIn(1);
    }

    public function justExpired()
    {
        return $this->withSellIn(0);
    }

    public function expired()
    {
        return $this->withSellIn(-3);
    }

    public function toSellIn($days)
    {
        return $this->withSellIn($days)->item();
    }

    public function withSellIn($days)
    {
        $this->sellIn = $days;

        return $this;
    }

    public function ofQuality($number)
    {
        return $this->withQuality($number)->item();
    }

    public function ofNoQuality()
    {
        return $this->withQuality(self::NO_QUALITY)->item();
    }

    public function ofMaxQuality()
    {
        return $this->withQuality(self::MAX_QUALITY)->item();
    }

    private function withQuality($number)
    {
        $this->quality = $number;

        return $this;
    }

    public function item()
    {
        return $this->set($this->build());
    }

    private function build()
    {
        return new Item($this->name, $this->sellIn, $this->quality);
    }

    private function set(Item $item)
    {
        $this->itemSetter->__invoke($item);

        return $item;
    }

    public function initialQuality()
    {
        return $this->quality;
    }

}
