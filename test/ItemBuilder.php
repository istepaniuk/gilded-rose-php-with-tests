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

    function __construct($itemSetter)
    {
        $this->itemSetter = $itemSetter;
        $this->sellIn = self::FRESH;
        $this->quality = 10;
    }

    function ordinaryItem()
    {
        return $this->named("any ordinary item");
    }

    function agedBrie()
    {
        return $this->named("Aged Brie");
    }

    function sulfuras()
    {
        return $this->named("Sulfuras, Hand of Ragnaros");
    }

    function backstagePass()
    {
        return $this->named("Backstage passes to a TAFKAL80ETC concert");
    }

    function conjuredItem()
    {
        return $this->named("Conjured Mana Cake");
    }

    private function named(string $itemName)
    {
        $this->name = $itemName;

        return $this;
    }

    function almostExpired()
    {
        return $this->withSellIn(1);
    }

    function justExpired()
    {
        return $this->withSellIn(0);
    }

    function expired()
    {
        return $this->withSellIn(-3);
    }

    function toSellIn($days)
    {
        return $this->withSellIn($days)->item();
    }

    function withSellIn($days)
    {
        $this->sellIn = $days;

        return $this;
    }

    function ofQuality($number)
    {
        return $this->withQuality($number)->item();
    }

    function ofNoQuality()
    {
        return $this->withQuality(self::NO_QUALITY)->item();
    }

    function ofMaxQuality()
    {
        return $this->withQuality(self::MAX_QUALITY)->item();
    }

    private function withQuality($number)
    {
        $this->quality = $number;

        return $this;
    }

    function item()
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
