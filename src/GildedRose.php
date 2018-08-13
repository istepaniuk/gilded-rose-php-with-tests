<?php

namespace GildedRose;

class GildedRose {

    public $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality() {
        for ($i = 0; $i < count($this->items); $i++) {
            if ($this->items[$i]->name != 'Aged Brie' and $this->items[$i]->name != 'Backstage passes to a TAFKAL80ETC concert') {
                if ($this->items[$i]->quality > 0) {
                    if ($this->items[$i]->name != 'Sulfuras, Hand of Ragnaros') {
                        $this->items[$i]->quality = $this->items[$i]->quality - 1;
                    }
                }
            } else {
                if ($this->items[$i]->quality < 50) {
                    $this->items[$i]->quality = $this->items[$i]->quality + 1;
            if ($this->items[$i]->name == 'Backstage passes to a TAFKAL80ETC concert') {
                if ($this->items[$i]->sellIn < 11)
                {
                    if ($this->items[$i]->quality < 50) { $this->items[$i]->quality = $this->items[$i]->quality + 1;}
                }
                if ($this->items[$i]->sellIn < 6) {
                    if ($this->items[$i]->quality < 50) {
                        $this->items[$i]->quality = $this->items[$i]->quality + 1;
                    }
                }
            }
                }
            }

            if ($this->items[$i]->name != 'Sulfuras, Hand of Ragnaros') {
                $this->items[$i]->sellIn = $this->items[$i]->sellIn - 1; }
		    if ($this->items[$i]->sellIn < 0) {
		        if ($this->items[$i]->name != 'Aged Brie') {
		            if ($this->items[$i]->name != 'Backstage passes to a TAFKAL80ETC concert') {
		                if ($this->items[$i]->quality > 0) {
		            if ($this->items[$i]->name != 'Sulfuras, Hand of Ragnaros') {
		                $this->items[$i]->quality = $this->items[$i]->quality - 1;
		            }
		                }
		            } else {
		                $this->items[$i]->quality = $this->items[$i]->quality - $this->items[$i]->quality;
		            }
		        } else 
		            if ($this->items[$i]->quality < 50) {
		                $this->items[$i]->quality = $this->items[$i]->quality + 1;
		            }
                
            }
        }
    }
}



