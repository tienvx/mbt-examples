<?php

namespace App\PageObjects;

class ProductPage
{
    public static $product = '#product-product';
    public static $buttonCart = 'button-cart';
    public static $textbox = 'input-option208';
    public static $selectbox = 'input-option217';
    public static $textarea = 'input-option209';
    public static $buttonAddToWishlist = "//button[@data-original-title='Add to Wish List']";
    public static $buttonCompareThisProduct = "//button[@data-original-title='Compare this Product']";
    public static $linkWriteAReview = 'Write a review';
    public static $inputName = 'input-name';
    public static $inputReview = 'input-review';
    public static $buttonReview = 'button-review';

    public static function radio($radio)
    {
        return "//input[@name='option[218]' and @value='{$radio}']";
    }

    public static function checkbox($checkbox)
    {
        return "//input[@name='option[223][]' and @value='{$checkbox}']";
    }

    public static function rating($rating)
    {
        return "//input[@name='rating' and @value='{$rating}']";
    }
}
