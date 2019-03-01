<?php

namespace App\Subject;

use App\Helper\ElementHelper;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Exception\UnexpectedTagNameException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverSelect;
use Symfony\Component\Panther\Client;
use Tienvx\Bundle\MbtBundle\Subject\AbstractSubject;

class Checkout extends AbstractSubject
{
    use ElementHelper;

    /**
     * @var int
     */
    protected $productId = 47;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string $url
     */
    protected $url = 'http://example.com';

    /**
     * @var bool
     */
    protected $loggedIn = false;

    /**
     * @var bool
     */
    protected $guestCheckout = false;

    /**
     * @var bool
     */
    protected $registerAccount = false;

    public static function support(): string
    {
        return 'checkout';
    }

    public function setUp()
    {
        $this->client = Client::createChromeClient();
    }

    public function tearDown()
    {
        $this->client->quit();
    }

    public function loggedIn()
    {
        return $this->loggedIn;
    }

    public function doingGuestCheckout()
    {
        return $this->guestCheckout;
    }

    public function doingRegisterAccount()
    {
        return $this->registerAccount;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function login()
    {
        // Email
        $by = WebDriverBy::id('input-email');
        $this->client->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated($by)
        );
        $element = $this->client->findElement($by);
        $element->sendKeys('test@example.com');
        // Password
        $by = WebDriverBy::id('input-password');
        $this->client->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated($by)
        );
        $element = $this->client->findElement($by);
        $element->sendKeys('1234');
        // Submit
        $by = WebDriverBy::id('button-login');
        $this->client->wait()->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();

        try {
            $by = WebDriverBy::cssSelector('#collapse-payment-address .panel-body .row');
            $this->client->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($by)
            );
        }
        catch (NoSuchElementException $e) {
            // It's okay, we are waiting for element to be loaded by ajax and appear in the page.
        }
        $this->loggedIn = true;
    }

    public function guestCheckout()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='account' and @value='guest']"))->click();
        $this->client->findElement(WebDriverBy::id('button-account'))->click();
        try {
            $by = WebDriverBy::cssSelector('#collapse-payment-address .panel-body .row');
            $this->client->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($by)
            );
        }
        catch (NoSuchElementException $e) {
            // It's okay, we are waiting for element to be loaded by ajax and appear in the page.
        }
        $this->guestCheckout = true;
    }

    public function registerAccount()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='account' and @value='register']"))->click();
        $this->client->findElement(WebDriverBy::id('button-account'))->click();
        try {
            $by = WebDriverBy::cssSelector('#collapse-payment-address .panel-body .row');
            $this->client->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($by)
            );
        }
        catch (NoSuchElementException $e) {
            // It's okay, we are waiting for element to be loaded by ajax and appear in the page.
        }
        $this->registerAccount = true;
    }

    public function hasExistingBillingAddress()
    {
        $element = $this->client->findElement(WebDriverBy::id('collapse-payment-address'));
        return strpos($element->getText(), 'I want to use an existing address') !== false;
    }

    public function useExistingBillingAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='payment_address' and @value='existing']"))->click();
        $this->client->findElement(WebDriverBy::id('button-payment-address'))->click();
    }

    /**
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     */
    public function useNewBillingAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='payment_address' and @value='new']"))->click();

        $this->client->findElement(WebDriverBy::id('input-payment-firstname'))->sendKeys('First');
        $this->client->findElement(WebDriverBy::id('input-payment-lastname'))->sendKeys('Last');
        $this->client->findElement(WebDriverBy::id('input-payment-address-1'))->sendKeys('Here');
        $this->client->findElement(WebDriverBy::id('input-payment-city'))->sendKeys('There');
        $this->client->findElement(WebDriverBy::id('input-payment-postcode'))->sendKeys('1234');
        $regionElement = $this->client->findElement(WebDriverBy::id('input-payment-zone'));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue('3513');
    }

    public function addBillingAddress()
    {
        $this->client->findElement(WebDriverBy::id('button-payment-address'))->click();
    }

    public function useExistingDeliveryAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='shipping_address' and @value='existing']"))->click();
        $this->client->findElement(WebDriverBy::id('button-shipping-address'))->click();
    }

    /**
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     */
    public function useNewDeliveryAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='shipping_address' and @value='new']"))->click();

        $this->client->findElement(WebDriverBy::id('input-shipping-firstname'))->sendKeys('First');
        $this->client->findElement(WebDriverBy::id('input-shipping-lastname'))->sendKeys('Last');
        $this->client->findElement(WebDriverBy::id('input-shipping-address-1'))->sendKeys('Here');
        $this->client->findElement(WebDriverBy::id('input-shipping-city'))->sendKeys('There');
        $this->client->findElement(WebDriverBy::id('input-shipping-postcode'))->sendKeys('1234');
        $regionElement = $this->client->findElement(WebDriverBy::id('input-shipping-zone'));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue('3513');
    }

    public function addDeliveryAddress()
    {
        $this->client->findElement(WebDriverBy::id('button-shipping-address'))->click();
    }

    public function addDeliveryMethod()
    {
        $this->client->findElement(WebDriverBy::id('button-shipping-method'))->click();
    }

    public function addPaymentMethod()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='agree']"))->click();
        $this->client->findElement(WebDriverBy::id('button-payment-method'))->click();
    }

    public function confirmOrder()
    {
        $this->client->findElement(WebDriverBy::id('button-confirm'))->click();
    }

    public function continueShopping()
    {
        $this->client->findElement(WebDriverBy::linkText('Continue'))->click();
    }

    public function fillPersonalDetails()
    {
        $this->client->findElement(WebDriverBy::id('input-payment-firstname'))->sendKeys('First');
        $this->client->findElement(WebDriverBy::id('input-payment-lastname'))->sendKeys('Last');
        $this->client->findElement(WebDriverBy::id('input-payment-email'))->sendKeys(uniqid() . '@example.com');
        $this->client->findElement(WebDriverBy::id('input-payment-telephone'))->sendKeys('0123456789');
    }

    public function fillPassword()
    {
        $this->client->findElement(WebDriverBy::id('input-payment-password'))->sendKeys('1234');
        $this->client->findElement(WebDriverBy::id('input-payment-confirm'))->sendKeys('1234');
    }

    /**
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     */
    public function fillBillingAddress()
    {
        $this->client->findElement(WebDriverBy::id('input-payment-address-1'))->sendKeys('Here');
        $this->client->findElement(WebDriverBy::id('input-payment-city'))->sendKeys('There');
        $this->client->findElement(WebDriverBy::id('input-payment-postcode'))->sendKeys('1234');
        $regionElement = $this->client->findElement(WebDriverBy::id('input-payment-zone'));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue('3513');
    }

    public function guestCheckoutAndAddBillingAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='shipping_address' and @value='existing']"))->click();
        $this->client->findElement(WebDriverBy::id('button-guest'))->click();
        $this->guestCheckout = false;
    }

    /**
     * @throws Exception
     */
    public function registerAndAddBillingAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='agree']"))->click();
        $this->client->findElement(WebDriverBy::id('button-register'))->click();

        $this->registerAccount = false;
        $this->loggedIn = true;
        if (!$this->testing) {
            throw new Exception('Still able to do register account, guest checkout or login when logged in!');
        }
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function addProductAndCheckoutNotLoggedIn()
    {
        $this->goToProduct($this->productId);
        $this->addToCart();
        $this->goToCheckout();
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function addProductAndCheckoutLoggedIn()
    {
        $this->goToProduct($this->productId);
        $this->addToCart();
        $this->goToCheckout();
    }

    /**
     * @param $id
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    private function goToProduct($id)
    {
        $this->client->get($this->url . "/index.php?route=product/product&product_id=$id");
        $this->client->waitFor('#product-product');
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    private function addToCart()
    {
        $by = WebDriverBy::id('button-cart');
        $this->client->wait()->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
        $this->client->wait()->until(
            WebDriverExpectedCondition::elementTextContains(WebDriverBy::className('alert'), 'Success')
        );
    }

    private function goToCheckout()
    {
        $this->client->get($this->url . '/index.php?route=checkout/checkout');
        try {
            $by = WebDriverBy::cssSelector('.panel-body .row');
            $this->client->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($by)
            );
        }
        catch (NoSuchElementException $e) {
            // It's okay, we are waiting for element to be loaded by ajax and appear in the page.
        }
    }

    public function captureScreenshot($bugId, $index)
    {
        if (!is_dir($this->screenshotsDir . "/{$bugId}")) {
            mkdir($this->screenshotsDir . "/{$bugId}", 0777, true);
        }
        $this->client->takeScreenshot($this->screenshotsDir . "/{$bugId}/{$index}.png");
    }
}
