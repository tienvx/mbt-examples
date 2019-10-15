<?php

namespace App\PageObjects;

class CartPage
{
    public static function remove($product)
    {
        return "button[onclick=\"cart.remove('$product');\"]";
    }

    public static function quantity($product)
    {
        return "input[name=\"quantity[$product]\"]";
    }

    public static function quantityUpdate($product)
    {
        return "input[name=\"quantity[$product]\"]+.input-group-btn>button[data-original-title=\"Update\"]";
    }
}
