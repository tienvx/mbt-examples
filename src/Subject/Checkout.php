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
        $this->goToHome();
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
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated($by)
        );
        $element = $this->client->findElement($by);
        $element->sendKeys('test@example.com');
        // Password
        $by = WebDriverBy::id('input-password');
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated($by)
        );
        $element = $this->client->findElement($by);
        $element->sendKeys('1234');
        // Submit
        $by = WebDriverBy::id('button-login');
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();

        $by = WebDriverBy::cssSelector('#collapse-payment-address .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);

        $this->loggedIn = true;
    }

    /**
     * @throws TimeOutException
     */
    public function guestCheckout()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='account' and @value='guest']"))->click();
        $this->client->findElement(WebDriverBy::id('button-account'))->click();

        $by = WebDriverBy::cssSelector('#collapse-payment-address .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);

        $this->guestCheckout = true;
    }

    /**
     * @throws TimeOutException
     */
    public function registerAccount()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='account' and @value='register']"))->click();
        $this->client->findElement(WebDriverBy::id('button-account'))->click();

        $by = WebDriverBy::cssSelector('#collapse-payment-address .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);

        $this->registerAccount = true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function hasExistingBillingAddress()
    {
        $by = WebDriverBy::xpath("//input[@name='payment_address' and @value='existing']");
        try {
            $this->client->wait(3)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($by)
            );
            return true;
        }
        catch (NoSuchElementException $e) {
            return false;
        }
        catch (TimeOutException $e) {
            return false;
        }
    }

    /**
     * @throws TimeOutException
     */
    public function useExistingBillingAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='payment_address' and @value='existing']"))->click();
        $this->client->findElement(WebDriverBy::id('button-payment-address'))->click();

        $by = WebDriverBy::cssSelector('#collapse-shipping-address .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function useNewBillingAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='payment_address' and @value='new']"))->click();

        $this->client->findElement(WebDriverBy::id('input-payment-firstname'))->sendKeys('First');
        $this->client->findElement(WebDriverBy::id('input-payment-lastname'))->sendKeys('Last');
        $this->client->findElement(WebDriverBy::id('input-payment-address-1'))->sendKeys('Here');
        $this->client->findElement(WebDriverBy::id('input-payment-city'))->sendKeys('There');
        // Postcode, country, region/state are pre-filled, but clear postcode is enough
        $this->client->findElement(WebDriverBy::id('input-payment-postcode'))->clear()->sendKeys('1234');
        $regionElement = $this->client->findElement(WebDriverBy::id('input-payment-zone'));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue('3513');

        $this->client->findElement(WebDriverBy::id('button-payment-address'))->click();

        $by = WebDriverBy::cssSelector('#collapse-shipping-address .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function addBillingAddress()
    {
        $this->client->findElement(WebDriverBy::id('input-payment-firstname'))->sendKeys('First');
        $this->client->findElement(WebDriverBy::id('input-payment-lastname'))->sendKeys('Last');
        $this->client->findElement(WebDriverBy::id('input-payment-address-1'))->sendKeys('Here');
        $this->client->findElement(WebDriverBy::id('input-payment-city'))->sendKeys('There');
        // Postcode, country, region/state are pre-filled, but clear postcode is enough
        $this->client->findElement(WebDriverBy::id('input-payment-postcode'))->clear()->sendKeys('1234');
        $regionElement = $this->client->findElement(WebDriverBy::id('input-payment-zone'));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue('3513');

        $this->client->findElement(WebDriverBy::id('button-payment-address'))->click();

        $by = WebDriverBy::cssSelector('#collapse-shipping-address .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @throws TimeOutException
     */
    public function useExistingDeliveryAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='shipping_address' and @value='existing']"))->click();
        $this->client->findElement(WebDriverBy::id('button-shipping-address'))->click();

        $by = WebDriverBy::cssSelector('#collapse-shipping-method .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function useNewDeliveryAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='shipping_address' and @value='new']"))->click();

        $this->client->findElement(WebDriverBy::id('input-shipping-firstname'))->sendKeys('First');
        $this->client->findElement(WebDriverBy::id('input-shipping-lastname'))->sendKeys('Last');
        $this->client->findElement(WebDriverBy::id('input-shipping-address-1'))->sendKeys('Here');
        $this->client->findElement(WebDriverBy::id('input-shipping-city'))->sendKeys('There');
        // Postcode, country, region/state are pre-filled, but clear postcode is enough
        $this->client->findElement(WebDriverBy::id('input-shipping-postcode'))->clear()->sendKeys('1234');
        $regionElement = $this->client->findElement(WebDriverBy::id('input-shipping-zone'));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue('3513');

        $this->client->findElement(WebDriverBy::id('button-shipping-address'))->click();

        $by = WebDriverBy::cssSelector('#collapse-shipping-method .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     * @throws TimeOutException
     * @throws Exception
     */
    public function addDeliveryAddress()
    {
        $this->client->findElement(WebDriverBy::id('input-shipping-firstname'))->sendKeys('First');
        $this->client->findElement(WebDriverBy::id('input-shipping-lastname'))->sendKeys('Last');
        $this->client->findElement(WebDriverBy::id('input-shipping-address-1'))->sendKeys('Here');
        $this->client->findElement(WebDriverBy::id('input-shipping-city'))->sendKeys('There');
        // Postcode, country, region/state are pre-filled, but clear postcode is enough
        $this->client->findElement(WebDriverBy::id('input-shipping-postcode'))->clear()->sendKeys('1234');
        $regionElement = $this->client->findElement(WebDriverBy::id('input-shipping-zone'));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue('3513');

        if ($this->hasExistingDeliveryAddress()) {
            $this->client->findElement(WebDriverBy::id('button-shipping-address'))->click();
        } else {
            try {
                $this->client->findElement(WebDriverBy::id('button-guest-shipping'))->click();
            }
            catch (NoSuchElementException $e) {
                $this->client->findElement(WebDriverBy::cssSelector("#collapse-shipping-address input[type='button']"))->click();
            }
        }

        $by = WebDriverBy::cssSelector('#collapse-shipping-method .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function hasExistingDeliveryAddress()
    {
        $by = WebDriverBy::xpath("//input[@name='shipping_address' and @value='existing']");
        try {
            $this->client->wait(3)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($by)
            );
            return true;
        }
        catch (NoSuchElementException $e) {
            return false;
        }
        catch (TimeOutException $e) {
            return false;
        }
    }

    /**
     * @throws TimeOutException
     */
    public function addDeliveryMethod()
    {
        $this->client->findElement(WebDriverBy::id('button-shipping-method'))->click();

        $by = WebDriverBy::cssSelector('#collapse-payment-method .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @throws TimeOutException
     */
    public function addPaymentMethod()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='agree']"))->click();
        $this->client->findElement(WebDriverBy::id('button-payment-method'))->click();

        $by = WebDriverBy::cssSelector('#collapse-checkout-confirm .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function confirmOrder()
    {
        $this->client->findElement(WebDriverBy::id('button-confirm'))->click();
        try {
            $this->client->wait(3)->until(
                WebDriverExpectedCondition::urlContains($this->url . '/index.php?route=checkout/success')

            );
        }
        catch (TimeOutException $e) {
            throw new TimeOutException(sprintf('Current url %s does not contain %s ', $this->client->getCurrentURL(), $this->url . '/index.php?route=checkout/success'));
        }
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function continueShopping()
    {
        $this->client->findElement(WebDriverBy::linkText('Continue'))->click();
        try {
            $this->client->wait(3)->until(
                WebDriverExpectedCondition::urlContains($this->url . '/index.php?route=common/home')

            );
        }
        catch (TimeOutException $e) {
            throw new TimeOutException(sprintf('Current url %s does not contain %s ', $this->client->getCurrentURL(), $this->url . '/index.php?route=common/home'));
        }
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

    /**
     * @throws TimeOutException
     */
    public function guestCheckoutAndAddBillingAddress()
    {
        // Delivery and billing addresses are not the same.
        $this->client->findElement(WebDriverBy::xpath("//input[@name='shipping_address' and @value='1']"))->click();
        $this->client->findElement(WebDriverBy::id('button-guest'))->click();

        $by = WebDriverBy::cssSelector('#collapse-shipping-address .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);

        $this->guestCheckout = false;
    }

    /**
     * @throws Exception
     */
    public function registerAndAddBillingAddress()
    {
        $this->client->findElement(WebDriverBy::xpath("//input[@name='agree']"))->click();
        $this->client->findElement(WebDriverBy::xpath("//input[@name='shipping_address' and @value='1']"))->click();
        $this->client->findElement(WebDriverBy::id('button-register'))->click();

        $by = WebDriverBy::cssSelector('#collapse-shipping-address .panel-body :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);

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
        $this->client->waitFor('#product-product', 3);
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    private function addToCart()
    {
        $by = WebDriverBy::id('button-cart');
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::elementTextContains(WebDriverBy::className('alert'), 'Success')
        );
    }

    /**
     * @throws TimeOutException
     */
    private function goToCheckout()
    {
        $this->client->get($this->url . '/index.php?route=checkout/checkout');

        $by = WebDriverBy::cssSelector('.panel-body  :first-child');
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    public function captureScreenshot($bugId, $index)
    {
        if (!is_dir($this->screenshotsDir . "/{$bugId}")) {
            mkdir($this->screenshotsDir . "/{$bugId}", 0777, true);
        }
        $this->client->takeScreenshot($this->screenshotsDir . "/{$bugId}/{$index}.png");
    }

    /**
     * @param WebDriverBy $by
     * @throws TimeOutException
     */
    public function waitUntilVisibilityOfElementLocated(WebDriverBy $by)
    {
        try {
            $this->client->wait(3)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($by)
            );
        }
        catch (NoSuchElementException $e) {
            // It's okay, we are waiting for element to be loaded by ajax and appear in the page.
        }
    }

    private function goToHome()
    {
        $this->client->get($this->url . '/index.php?route=common/home');
    }
}
