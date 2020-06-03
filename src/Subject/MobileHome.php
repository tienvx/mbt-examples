<?php

namespace App\Subject;

use App\Helper\SetUp;
use App\PageObjects\HomePage;
use App\PageObjects\MasterPage;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Symfony\Component\Process\Process;
use Tienvx\Bundle\MbtBundle\Annotation\Subject;
use Tienvx\Bundle\MbtBundle\Annotation\Transition;
use Tienvx\Bundle\MbtBundle\Steps\Data;
use Tienvx\Bundle\MbtBundle\Subject\AbstractSubject;

/**
 * @Subject("mobile_home")
 */
class MobileHome extends AbstractSubject
{
    use SetUp;

    /**
     * @var bool
     */
    protected $cartOpen = false;

    /**
     * @var string
     */
    protected $url = 'https://opencart.mbtbundle.org';

    /**
     * @var array
     */
    protected $cart;

    /**
     * @var array
     */
    protected $products = [
        '43', // 'MacBook',
        '40', // 'iPhone',
        '42', // 'Apple Cinema 30',
        '30', // 'Canon EOS 5D',
    ];

    /**
     * @var array
     */
    protected $canAddProducts = [
        '43', // 'MacBook',
        '40', // 'iPhone',
    ];

    public function __construct()
    {
        $this->cart = [];
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function setUp(bool $trying = false): void
    {
        $this->android($trying);
        $this->goToHome();
    }

    public function tearDown(): void
    {
        $this->client->quit();
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    private function goToHome()
    {
        $this->client->get($this->url);
        $this->client->waitFor(HomePage::$home, 30);
    }

    /**
     * @Transition("add")
     *
     * @throws Exception
     */
    public function add(Data $data)
    {
        if ($data->has('product')) {
            $product = $data->get('product');
            if (empty($this->canAddProducts[$product])) {
                throw new Exception('Selected product can not be added');
            }
        } else {
            $product = $this->canAddProducts[array_rand($this->canAddProducts)];
            $data->set('product', $product);
        }
        if (!isset($this->cart[$product])) {
            $this->cart[$product] = 1;
        } else {
            ++$this->cart[$product];
        }
        $by = WebDriverBy::cssSelector(HomePage::addToCart($product));
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
        $this->closeAlerts();
    }

    /**
     * @Transition("wish")
     *
     * @throws Exception
     */
    public function wish(Data $data)
    {
        $product = $data->getSet('product', [$this, 'randomProductFromHome'], [$this, 'validateProductFromHome']);

        $by = WebDriverBy::cssSelector(HomePage::addWishlist($product));
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
        $this->closeAlerts();
    }

    /**
     * @Transition("compare")
     *
     * @throws Exception
     */
    public function compare(Data $data)
    {
        $product = $data->getSet('product', [$this, 'randomProductFromHome'], [$this, 'validateProductFromHome']);

        $by = WebDriverBy::cssSelector(HomePage::compare($product));
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
        $this->closeAlerts();
    }

    public function randomProductFromHome()
    {
        return $this->products[array_rand($this->products)];
    }

    /**
     * @param $product
     *
     * @throws Exception
     */
    public function validateProductFromHome($product)
    {
        if (empty($this->products[$product])) {
            throw new Exception('Selected product is not in home page');
        }
    }

    /**
     * @Transition("openCart")
     *
     * @throws Exception
     */
    public function openCart()
    {
        $by = WebDriverBy::cssSelector(HomePage::$cart);
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();

        $this->cartOpen = true;
    }

    /**
     * @Transition("closeCart")
     *
     * @throws Exception
     */
    public function closeCart()
    {
        $by = WebDriverBy::cssSelector(HomePage::$cart);
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();

        $this->cartOpen = false;
    }

    /**
     * @Transition("remove")
     *
     * @throws Exception
     */
    public function remove(Data $data)
    {
        if ($data->has('product')) {
            $product = $data->get('product');
            if (empty($this->cart[$product])) {
                throw new Exception('Selected product is not in cart');
            }
        } else {
            $product = array_rand($this->cart);
            $data->set('product', $product);
        }
        unset($this->cart[$product]);

        // TODO id is not product id
        $by = WebDriverBy::cssSelector(HomePage::removeFromCart($product));
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
    }

    public function cartHasProducts()
    {
        return !empty($this->cart);
    }

    public function cartIsOpen()
    {
        return $this->cartOpen;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function closeAlerts()
    {
        $this->client->waitFor(MasterPage::$alert, 3);
        $by = WebDriverBy::cssSelector(MasterPage::$closeAlert);
        sleep(1);
        $this->client->wait(3)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
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
}
