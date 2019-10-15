<?php

namespace App\PageObjects;

class HomePage
{
    public static $home = '#common-home';
    public static $cart = '#cart';

    public static function addToCart($product)
    {
        return "button[onclick*=\"cart.add('$product'\"]";
    }

    public static function removeFromCart($product)
    {
        return "button[onclick*=\"cart.remove('$product'\"]";
    }

    public static function addWishlist($product)
    {
        return "button[onclick*=\"wishlist.add('$product'\"]";
    }

    public static function compare($product)
    {
        return "button[onclick*=\"compare.add('$product'\"]";
    }
}
