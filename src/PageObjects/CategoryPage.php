<?php

namespace App\PageObjects;

class CategoryPage
{
    public static function addToCart($product)
    {
        return "button[onclick*=\"cart.add('$product'\"]";
    }
}
