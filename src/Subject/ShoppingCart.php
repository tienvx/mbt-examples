<?php

namespace App\Subject;

use App\Helper\ElementHelper;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Tienvx\Bundle\MbtBundle\Annotation\DataProvider;
use Tienvx\Bundle\MbtBundle\Subject\Subject;

class ShoppingCart extends Subject
{
    use ElementHelper;

    /**
     * @var RemoteWebDriver
     */
    protected $webDriver;

    /**
     * @var string $url
     */
    protected $url = 'http://example.com';

    /**
     * @var array
     */
    protected $cart;

    /**
     * @var string Current viewing product
     */
    protected $product;

    /**
     * @var string Current viewing category
     */
    protected $category;

    /**
     * @var array
     */
    protected $featuredProducts = [
        '43', // 'MacBook',
        '40', // 'iPhone',
        '42', // 'Apple Cinema 30',
        '30', // 'Canon EOS 5D',
    ];

    /**
     * @var array
     */
    protected $categories = [
        '20', // 'Desktops',
        '20_27', // 'Mac',
        '18', // 'Laptops & Notebooks',
        '25', // 'Components',
        '25_28', // 'Monitors',
        '57', // 'Tablets',
        '17', // 'Software',
        '24', // 'Phones & PDAs',
        '33', // 'Cameras',
        '34', // 'MP3 Players',
    ];

    /**
     * @var array
     */
    protected $productsInCategory = [
        '20' => [
            '42', // 'Apple Cinema 30',
            '30', // 'Canon EOS 5D',
            '47', // 'HP LP3065',
            '28', // 'HTC Touch HD',
            '40', // 'iPhone',
            '48', // 'iPod Classic',
            '43', // 'MacBook',
            '42', // 'Apple Cinema 30',
            '44', // 'MacBook Air',
            '29', // 'Palm Treo Pro',
            '35', // 'Product 8',
            '33', // 'Samsung SyncMaster 941BW',
            '46', // 'Sony VAIO',
        ],
        '20_27' => [
            '41', // 'iMac',
        ],
        '18' => [
            '47', // 'HP LP3065',
            '43', // 'MacBook',
            '44', // 'MacBook Air',
            '45', // 'MacBook Pro',
            '46', // 'Sony VAIO',
        ],
        '25' => [],
        '25_28' => [
            '42', // 'Apple Cinema 30',
            '33', // 'Samsung SyncMaster 941BW'
        ],
        '57' => [
            '49', // 'Samsung Galaxy Tab 10.1',
        ],
        '17' => [],
        '24' => [
            '28', // 'HTC Touch HD',
            '40', // 'iPhone',
            '29', // 'Palm Treo Pro',
        ],
        '33' => [
            '30', // 'Canon EOS 5D',
            '31', // 'Nikon D300'
        ],
        '34' => [
            '48', // 'iPod Classic',
            '36', // 'iPod Nano',
            '34', // 'iPod Shuffle',
            '32', // 'iPod Touch',
        ],
    ];

    /**
     * @var array
     */
    protected $outOfStock = [
        '49', // 'Samsung Galaxy Tab 10.1',
    ];

    public function __construct()
    {
        $this->cart = [];
        $this->category = null;
        $this->product = null;
    }

    public function setUp()
    {
        $this->webDriver = RemoteWebDriver::create('http://selenium-hub:4444/wd/hub', DesiredCapabilities::chrome());
    }

    public function tearDown()
    {
        $this->webDriver->close();
        $this->webDriver->quit();
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomCategory")
     */
    public function viewAnyCategoryFromHome()
    {
        if (empty($this->data['category'])) {
            throw new Exception('Can not view category from home: category is not selected');
        }
        $category = $this->data['category'];
        $this->category = $category;
        $this->product = null;
        $this->goToCategory($category);
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomCategory")
     */
    public function viewOtherCategory()
    {
        if (empty($this->data['category'])) {
            throw new Exception('Can not view category from other category: category is not selected');
        }
        $category = $this->data['category'];
        $this->category = $category;
        $this->product = null;
        $this->goToCategory($category);
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomCategory")
     */
    public function viewAnyCategoryFromProduct()
    {
        if (empty($this->data['category'])) {
            throw new Exception('Can not view category from product: category is not selected');
        }
        $category = $this->data['category'];
        $this->category = $category;
        $this->product = null;
        $this->goToCategory($category);
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomCategory")
     */
    public function viewAnyCategoryFromCart()
    {
        if (empty($this->data['category'])) {
            throw new Exception('Can not view category from cart: category is not selected');
        }
        $category = $this->data['category'];
        $this->category = $category;
        $this->product = null;
        $this->goToCategory($category);
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomProductFromHome")
     */
    public function viewProductFromHome()
    {
        if (empty($this->data['product'])) {
            throw new Exception('Can not view product from home: product is not selected');
        }
        $product = $this->data['product'];
        $this->product = $product;
        $this->category = null;
        $this->goToProduct($product);
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomProductFromCart")
     */
    public function viewProductFromCart()
    {
        if (empty($this->data['product'])) {
            throw new Exception('Can not view product from cart: product is not selected');
        }
        $product = $this->data['product'];
        $this->product = $product;
        $this->category = null;
        $this->goToProduct($product);
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomProductFromCategory")
     */
    public function viewProductFromCategory()
    {
        if (empty($this->data['product'])) {
            throw new Exception('Can not view product from category: product is not selected');
        }
        $product = $this->data['product'];
        $this->product = $product;
        $this->category = null;
        $this->goToProduct($product);
    }

    /**
     * @throws Exception
     */
    public function categoryHasSelectedProduct()
    {
        if (empty($this->productsInCategory[$this->category])) {
            return false;
        } else {
            if (empty($this->data['product'])) {
                throw new Exception('Can not check if category has selected product or not: product is not selected');
            }
            $product = $this->data['product'];
            return in_array($product, $this->productsInCategory[$this->category]);
        }
    }

    public function productHasBeenSelected()
    {
        return !empty($this->data['product']);
    }

    public function categoryHasBeenSelected()
    {
        return !empty($this->data['category']);
    }

    public function viewCartFromHome()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCart();
    }

    public function viewCartFromCategory()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCart();
    }

    public function viewCartFromProduct()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCart();
    }

    public function viewCartFromCheckout()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCart();
    }

    public function checkoutFromHome()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCheckout();
    }

    public function checkoutFromCategory()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCheckout();
    }

    public function checkoutFromProduct()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCheckout();
    }

    public function checkoutFromCart()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCheckout();
    }

    public function backToHomeFromCategory()
    {
        $this->category = null;
        $this->product = null;
        $this->goToHome();
    }

    public function backToHomeFromProduct()
    {
        $this->category = null;
        $this->product = null;
        $this->goToHome();
    }

    public function backToHomeFromCart()
    {
        $this->category = null;
        $this->product = null;
        $this->goToHome();
    }

    public function backToHomeFromCheckout()
    {
        $this->category = null;
        $this->product = null;
        $this->goToHome();
    }

    /**
     * @throws Exception
     */
    public function cartHasSelectedProduct()
    {
        if (empty($this->cart)) {
            return false;
        } else {
            if (empty($this->data['product'])) {
                throw new Exception('Can not check if cart has selected product or not: product is not selected');
            }
            $product = $this->data['product'];
            return $this->hasElement($this->webDriver, WebDriverBy::cssSelector("#checkout-cart button[onclick=\"cart.remove('$product');\"]"));
        }
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomProductFromHome")
     */
    public function addFromHome()
    {
        if (empty($this->data['product'])) {
            throw new Exception('Can not add product from home: product is not selected');
        }
        $product = $this->data['product'];
        if (!isset($this->cart[$product])) {
            $this->cart[$product] = 1;
        } else {
            $this->cart[$product]++;
        }
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomProductFromCategory")
     */
    public function addFromCategory()
    {
        if (empty($this->data['product'])) {
            throw new Exception('Can not add product from category: product is not selected');
        }
        $product = $this->data['product'];
        if (!isset($this->cart[$product])) {
            $this->cart[$product] = 1;
        } else {
            $this->cart[$product]++;
        }
        $this->webDriver->findElement(WebDriverBy::cssSelector("button[onclick*=\"cart.add('$product'\"]"))->click();
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function addFromProduct()
    {
        if (!isset($this->cart[$this->product])) {
            $this->cart[$this->product] = 1;
        }
        else {
            $this->cart[$this->product]++;
        }
        $this->webDriver->wait()->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::xpath("//button[text()='Add to Cart']")
            )
        );
        $this->webDriver->findElement(WebDriverBy::xpath("//button[text()='Add to Cart']"))->click();
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomProductFromCart")
     */
    public function remove()
    {
        if (empty($this->data['product'])) {
            throw new Exception('Can not remove product from cart: product is not selected');
        }
        $product = $this->data['product'];
        unset($this->cart[$product]);
        $this->webDriver->findElement(WebDriverBy::cssSelector("button[onclick=\"cart.remove('$product');\"]"))->click();
    }

    /**
     * @throws Exception
     * @DataProvider(method="getRandomProductFromCart")
     */
    public function update()
    {
        if (empty($this->data['product'])) {
            throw new Exception('Can not update product in cart: product is not selected');
        }
        $product = $this->data['product'];
        $quantity = rand(1, 99);
        $this->cart[$product] = $quantity;
        $this->webDriver->findElement(WebDriverBy::cssSelector("input[name=\"quantity[$product]\"]"))->sendKeys($quantity);
        $this->webDriver->findElement(WebDriverBy::cssSelector("input[name=\"quantity[$product]\"]+.input-group-btn>button[data-original-title=\"Update\"]"))->click();
    }

    public function home()
    {
    }

    public function category()
    {
    }

    public function product()
    {
    }

    public function cart()
    {
    }

    /**
     * @throws Exception
     */
    public function checkout()
    {
        if (!$this->testing) {
            foreach ($this->cart as $product => $quantity) {
                if (in_array($product, $this->outOfStock)) {
                    throw new Exception('You added an out-of-stock product into cart! Can not checkout');
                }
            }
        }
    }

    public function getRandomProductFromHome()
    {
        if (empty($this->featuredProducts)) {
            return null;
        }
        $product = $this->featuredProducts[array_rand($this->featuredProducts)];
        return ['product' => $product];
    }

    public function getRandomCategory()
    {
        if (empty($this->categories)) {
            return null;
        }
        $category = $this->categories[array_rand($this->categories)];
        return ['category' => $category];
    }

    public function getRandomProductFromCart()
    {
        if (empty($this->cart)) {
            return null;
        }
        $product = array_rand($this->cart);
        return ['product' => $product];
    }

    public function getRandomProductFromCategory()
    {
        if (!isset($this->productsInCategory[$this->category])) {
            return null;
        }
        $products = $this->productsInCategory[$this->category];
        if (empty($products)) {
            return null;
        }
        $product = $products[array_rand($products)];
        return ['product' => $product];
    }

    private function goToCategory($id)
    {
        $this->webDriver->get($this->url . "/index.php?route=product/category&path=$id");
    }

    /**
     * @param $id
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    private function goToProduct($id)
    {
        $this->webDriver->get($this->url . "/index.php?route=product/product&product_id=$id");
        $this->webDriver->wait()->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::cssSelector('#product-product')
            )
        );
    }

    private function goToCart()
    {
        $this->webDriver->get($this->url . '/index.php?route=checkout/cart');
    }

    private function goToCheckout()
    {
        $this->webDriver->get($this->url . '/index.php?route=checkout/checkout');
    }

    private function goToHome()
    {
        $this->webDriver->get($this->url . '/index.php?route=common/home');
    }
}
