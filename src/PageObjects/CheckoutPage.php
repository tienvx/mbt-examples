<?php

namespace App\PageObjects;

class CheckoutPage
{
    public static $checkout = '.panel-body :first-child';
    public static $inputEmail = 'input-email';
    public static $inputPassword = 'input-password';
    public static $buttonLogin = 'button-login';
    public static $paymentAddress = '#collapse-payment-address .panel-body :first-child';
    public static $radioGuest = "//input[@name='account' and @value='guest']";
    public static $buttonAccount = 'button-account';
    public static $radioRegister = "//input[@name='account' and @value='register']";
    public static $radioExistingPaymentAddress = "//input[@name='payment_address' and @value='existing']";
    public static $radioNewPaymentAddress = "//input[@name='payment_address' and @value='new']";
    public static $buttonPaymentAddress = 'button-payment-address';
    public static $shippingAddress = '#collapse-shipping-address .panel-body :first-child';
    public static $inputPaymentFirstname = 'input-payment-firstname';
    public static $inputPaymentLastname = 'input-payment-lastname';
    public static $inputPaymentAddress = 'input-payment-address-1';
    public static $inputPaymentCity = 'input-payment-city';
    public static $inputPaymentPostcode = 'input-payment-postcode';
    public static $inputPaymentZone = 'input-payment-zone';
    public static $inputPaymentEmail = 'input-payment-email';
    public static $inputPaymentTelephone = 'input-payment-telephone';
    public static $inputPaymentPassword = 'input-payment-password';
    public static $inputPaymentConfirm = 'input-payment-confirm';
    public static $buttonShippingAddress = 'button-shipping-address';
    public static $shippingMethod = '#collapse-shipping-method .panel-body :first-child';
    public static $radioExistingShippingAddress = "//input[@name='shipping_address' and @value='existing']";
    public static $radioNewShippingAddress = "//input[@name='shipping_address' and @value='new']";
    public static $inputShippingFirstname = 'input-shipping-firstname';
    public static $inputShippingLastname = 'input-shipping-lastname';
    public static $inputShippingAddress = 'input-shipping-address-1';
    public static $inputShippingCity = 'input-shipping-city';
    public static $inputShippingPostcode = 'input-shipping-postcode';
    public static $inputShippingZone = 'input-shipping-zone';
    public static $buttonGuestShipping = 'button-guest-shipping';
    public static $inputButtonShippingAddress = "#collapse-shipping-address input[type='button']";
    public static $buttonShippingMethod = 'button-shipping-method';
    public static $paymentMethod = '#collapse-payment-method .panel-body :first-child';
    public static $inputAgree = "//input[@name='agree']";
    public static $buttonPaymentMethod = 'button-payment-method';
    public static $checkoutConfirm = '#collapse-checkout-confirm .panel-body :first-child';
    public static $buttonConfirm = 'button-confirm';
    public static $continue = 'Continue';
    public static $buttonGuest = 'button-guest';
    public static $inputSameDeliveryAndBillingAddresses = "//input[@name='shipping_address' and @value='1']";
    public static $buttonRegister = 'button-register';
}
