<?php

namespace App\Subject;

use App\Helper\ElementHelper;
use App\Helper\SetUp;
use App\PageObjects\CheckoutPage;
use App\PageObjects\ProductPage;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Exception\UnexpectedTagNameException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverSelect;
use Symfony\Component\Process\Process;
use Tienvx\Bundle\MbtBundle\Annotation\Subject;
use Tienvx\Bundle\MbtBundle\Annotation\Transition;
use Tienvx\Bundle\MbtBundle\Subject\AbstractSubject;

/**
 * @Subject("checkout")
 */
class Checkout extends AbstractSubject
{
    use ElementHelper;
    use SetUp;

    /**
     * @var int
     */
    protected $productId = 47;

    /**
     * @var string
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

    public function setUp(bool $trying = false): void
    {
        $this->opera($trying);
        $this->goToHome();
    }

    public function tearDown(): void
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
     * @Transition("login")
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function login()
    {
        // Email
        $by = WebDriverBy::id(CheckoutPage::$inputEmail);
        $this->client->wait(1)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated($by)
        );
        $element = $this->client->findElement($by);
        $element->sendKeys('test@example.com');
        // Password
        $by = WebDriverBy::id(CheckoutPage::$inputPassword);
        $this->client->wait(1)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated($by)
        );
        $element = $this->client->findElement($by);
        $element->sendKeys('1234');
        // Submit
        $by = WebDriverBy::id(CheckoutPage::$buttonLogin);
        $this->client->wait(1)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();

        $by = WebDriverBy::cssSelector(CheckoutPage::$paymentAddress);
        $this->waitUntilVisibilityOfElementLocated($by);

        $this->loggedIn = true;
    }

    /**
     * @Transition("guestCheckout")
     *
     * @throws TimeOutException
     */
    public function guestCheckout()
    {
        $this->client->findElement(WebDriverBy::xpath(CheckoutPage::$radioGuest))->click();
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonAccount))->click();

        $by = WebDriverBy::cssSelector(CheckoutPage::$paymentAddress);
        $this->waitUntilVisibilityOfElementLocated($by);

        $this->guestCheckout = true;
    }

    /**
     * @Transition("registerAccount")
     *
     * @throws TimeOutException
     */
    public function registerAccount()
    {
        $this->client->findElement(WebDriverBy::xpath(CheckoutPage::$radioRegister))->click();
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonAccount))->click();

        $by = WebDriverBy::cssSelector(CheckoutPage::$paymentAddress);
        $this->waitUntilVisibilityOfElementLocated($by);

        $this->registerAccount = true;
    }

    /**
     * @return bool
     *
     * @throws Exception
     */
    public function hasExistingBillingAddress()
    {
        $by = WebDriverBy::xpath(CheckoutPage::$radioExistingPaymentAddress);
        try {
            $this->client->wait(1)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($by)
            );

            return true;
        } catch (NoSuchElementException $e) {
            return false;
        } catch (TimeOutException $e) {
            return false;
        }
    }

    /**
     * @Transition("useExistingBillingAddress")
     *
     * @throws TimeOutException
     */
    public function useExistingBillingAddress()
    {
        $this->client->findElement(WebDriverBy::xpath(CheckoutPage::$radioExistingPaymentAddress))->click();
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonPaymentAddress))->click();

        $by = WebDriverBy::cssSelector(CheckoutPage::$shippingAddress);
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @Transition("useNewBillingAddress")
     *
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function useNewBillingAddress()
    {
        $this->client->findElement(WebDriverBy::xpath(CheckoutPage::$radioNewPaymentAddress))->click();

        $this->addBillingAddress();
    }

    /**
     * @Transition("addBillingAddress")
     *
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function addBillingAddress()
    {
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentFirstname))->sendKeys('First');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentLastname))->sendKeys('Last');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentAddress))->sendKeys('Here');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentCity))->sendKeys('There');
        // Postcode, country, region/state are pre-filled, but clear postcode is enough
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentPostcode))->clear()->sendKeys('1234');
        $regionElement = $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentZone));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue('3513');

        $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonPaymentAddress))->click();

        $by = WebDriverBy::cssSelector(CheckoutPage::$shippingAddress);
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @Transition("useExistingDeliveryAddress")
     *
     * @throws TimeOutException
     */
    public function useExistingDeliveryAddress()
    {
        $this->client->findElement(WebDriverBy::xpath(CheckoutPage::$radioExistingShippingAddress))->click();
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonShippingAddress))->click();

        $by = WebDriverBy::cssSelector(CheckoutPage::$shippingMethod);
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @Transition("useNewDeliveryAddress")
     *
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function useNewDeliveryAddress()
    {
        $this->client->findElement(WebDriverBy::xpath(CheckoutPage::$radioNewShippingAddress))->click();

        $this->addDeliveryAddress();
    }

    /**
     * @Transition("addDeliveryAddress")
     *
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     * @throws TimeOutException
     * @throws Exception
     */
    public function addDeliveryAddress()
    {
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputShippingFirstname))->sendKeys('First');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputShippingLastname))->sendKeys('Last');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputShippingAddress))->sendKeys('Here');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputShippingCity))->sendKeys('There');
        // Postcode, country, region/state are pre-filled, but clear postcode is enough
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputShippingPostcode))->clear()->sendKeys('1234');
        $regionElement = $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputShippingZone));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue('3513');

        if ($this->hasExistingDeliveryAddress()) {
            $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonShippingAddress))->click();
        } else {
            try {
                $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonGuestShipping))->click();
            } catch (NoSuchElementException $e) {
                $this->client->findElement(WebDriverBy::cssSelector(CheckoutPage::$inputButtonShippingAddress))->click();
            }
        }

        $by = WebDriverBy::cssSelector(CheckoutPage::$shippingMethod);
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @return bool
     *
     * @throws Exception
     */
    public function hasExistingDeliveryAddress()
    {
        $by = WebDriverBy::xpath(CheckoutPage::$radioExistingShippingAddress);
        try {
            $this->client->wait(1)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($by)
            );

            return true;
        } catch (NoSuchElementException $e) {
            return false;
        } catch (TimeOutException $e) {
            return false;
        }
    }

    /**
     * @Transition("addDeliveryMethod")
     *
     * @throws TimeOutException
     */
    public function addDeliveryMethod()
    {
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonShippingMethod))->click();

        $by = WebDriverBy::cssSelector(CheckoutPage::$paymentMethod);
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @Transition("addPaymentMethod")
     *
     * @throws TimeOutException
     */
    public function addPaymentMethod()
    {
        $this->client->findElement(WebDriverBy::xpath(CheckoutPage::$inputAgree))->click();
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonPaymentMethod))->click();

        $by = WebDriverBy::cssSelector(CheckoutPage::$checkoutConfirm);
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    /**
     * @Transition("confirmOrder")
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function confirmOrder()
    {
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonConfirm))->click();
        try {
            $this->client->wait(1)->until(
                WebDriverExpectedCondition::urlContains($this->url.'/index.php?route=checkout/success')
            );
        } catch (TimeOutException $e) {
            throw new TimeOutException(sprintf('Current url %s does not contain %s ', $this->client->getCurrentURL(), $this->url.'/index.php?route=checkout/success'));
        }
    }

    /**
     * @Transition("continueShopping")
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function continueShopping()
    {
        $this->client->findElement(WebDriverBy::linkText(CheckoutPage::$continue))->click();
        try {
            $this->client->wait(1)->until(
                WebDriverExpectedCondition::urlContains($this->url.'/index.php?route=common/home')
            );
        } catch (TimeOutException $e) {
            throw new TimeOutException(sprintf('Current url %s does not contain %s ', $this->client->getCurrentURL(), $this->url.'/index.php?route=common/home'));
        }
    }

    /**
     * @Transition("fillPersonalDetails")
     */
    public function fillPersonalDetails()
    {
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentFirstname))->sendKeys('First');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentLastname))->sendKeys('Last');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentEmail))->sendKeys(uniqid().'@example.com');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentTelephone))->sendKeys('0123456789');
    }

    /**
     * @Transition("fillPassword")
     */
    public function fillPassword()
    {
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentPassword))->sendKeys('1234');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentConfirm))->sendKeys('1234');
    }

    /**
     * @Transition("fillBillingAddress")
     *
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     */
    public function fillBillingAddress()
    {
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentAddress))->sendKeys('Here');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentCity))->sendKeys('There');
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentPostcode))->sendKeys('1234');
        $regionElement = $this->client->findElement(WebDriverBy::id(CheckoutPage::$inputPaymentZone));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue('3513');
    }

    /**
     * @Transition("guestCheckoutAndAddBillingAddress")
     *
     * @throws TimeOutException
     */
    public function guestCheckoutAndAddBillingAddress()
    {
        // Delivery and billing addresses are not the same.
        $this->client->findElement(WebDriverBy::xpath(CheckoutPage::$inputSameDeliveryAndBillingAddresses))->click();
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonGuest))->click();

        $by = WebDriverBy::cssSelector(CheckoutPage::$shippingAddress);
        $this->waitUntilVisibilityOfElementLocated($by);

        $this->guestCheckout = false;
    }

    /**
     * @Transition("registerAndAddBillingAddress")
     *
     * @throws Exception
     */
    public function registerAndAddBillingAddress()
    {
        $this->client->findElement(WebDriverBy::xpath(CheckoutPage::$inputAgree))->click();
        $this->client->findElement(WebDriverBy::xpath(CheckoutPage::$inputSameDeliveryAndBillingAddresses))->click();
        $this->client->findElement(WebDriverBy::id(CheckoutPage::$buttonRegister))->click();

        $by = WebDriverBy::cssSelector(CheckoutPage::$shippingAddress);
        $this->waitUntilVisibilityOfElementLocated($by);

        $this->registerAccount = false;
        $this->loggedIn = true;
        throw new Exception('Still able to do register account, guest checkout or login when logged in!');
    }

    /**
     * @Transition("addProductAndCheckoutNotLoggedIn")
     *
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
     * @Transition("addProductAndCheckoutLoggedIn")
     *
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
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    private function goToProduct($id)
    {
        $this->client->get($this->url."/index.php?route=product/product&product_id=$id");
        $this->client->waitFor(ProductPage::$product, 1);
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    private function addToCart()
    {
        $by = WebDriverBy::id(ProductPage::$buttonCart);
        $this->client->wait(1)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
        $this->client->wait(1)->until(
            WebDriverExpectedCondition::elementTextContains(WebDriverBy::className('alert'), 'Success')
        );
    }

    /**
     * @throws TimeOutException
     */
    private function goToCheckout()
    {
        $this->client->get($this->url.'/index.php?route=checkout/checkout');

        $by = WebDriverBy::cssSelector(CheckoutPage::$checkout);
        $this->waitUntilVisibilityOfElementLocated($by);
    }

    public function captureScreenshot($bugId, $index): void
    {
        $this->client->takeScreenshot('/tmp/screenshot.png');

        $process = Process::fromShellCommandline('pngquant --quality=60-90 - < /tmp/screenshot.png');
        $process->run();

        $image = $process->getOutput();
        $this->filesystem->put("{$bugId}/{$index}.png", $image);

        unlink('/tmp/screenshot.png');
    }

    /**
     * @throws TimeOutException
     */
    public function waitUntilVisibilityOfElementLocated(WebDriverBy $by)
    {
        try {
            $this->client->wait(1)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($by)
            );
        } catch (NoSuchElementException $e) {
            // It's okay, we are waiting for element to be loaded by ajax and appear in the page.
        }
    }

    private function goToHome()
    {
        $this->client->get($this->url.'/index.php?route=common/home');
    }
}
