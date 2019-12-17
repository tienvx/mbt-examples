<?php

namespace App\Subject;

use App\Helper\ElementHelper;
use App\Helper\SetUp;
use App\PageObjects\CartPage;
use App\PageObjects\CategoryPage;
use App\PageObjects\ProductPage;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Symfony\Component\Process\Process;
use Tienvx\Bundle\MbtBundle\Annotation\Place;
use Tienvx\Bundle\MbtBundle\Annotation\Subject;
use Tienvx\Bundle\MbtBundle\Annotation\Transition;
use Tienvx\Bundle\MbtBundle\Steps\Data;
use Tienvx\Bundle\MbtBundle\Steps\DataHelper;
use Tienvx\Bundle\MbtBundle\Subject\AbstractSubject;

/**
 * @Subject("shopping_cart")
 */
class ShoppingCart extends AbstractSubject
{
    use ElementHelper;
    use SetUp;

    /**
     * @var string
     */
    protected $url = 'https://opencart.mbtbundle.org';

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

    /**
     * @var array
     */
    protected $needOptions = [
        '42', // 'Apple Cinema 30',
        '30', // 'Canon EOS 5D',
        '35', // 'Product 8',
    ];

    public function __construct()
    {
        $this->cart = [];
        $this->category = null;
        $this->product = null;
    }

    public function setUp(bool $testing = false): void
    {
        $this->chrome($testing);
        $this->goToHome();
    }

    public function tearDown(): void
    {
        $this->client->quit();
    }

    /**
     * @Transition("viewAnyCategoryFromHome")
     *
     * @throws Exception
     */
    public function viewAnyCategoryFromHome(Data $data)
    {
        $this->viewCategory($data);
    }

    /**
     * @throws Exception
     */
    private function viewCategory(Data $data)
    {
        $category = DataHelper::get($data, 'category', [$this, 'randomCategory'], [$this, 'validateCategory']);
        $this->category = $category;
        $this->product = null;
        $this->goToCategory($category);
    }

    /**
     * @Transition("viewOtherCategory")
     *
     * @throws Exception
     */
    public function viewOtherCategory(Data $data)
    {
        $this->viewCategory($data);
    }

    /**
     * @Transition("viewAnyCategoryFromProduct")
     *
     * @throws Exception
     */
    public function viewAnyCategoryFromProduct(Data $data)
    {
        $this->viewCategory($data);
    }

    /**
     * @Transition("viewAnyCategoryFromCart")
     *
     * @throws Exception
     */
    public function viewAnyCategoryFromCart(Data $data)
    {
        $this->viewCategory($data);
    }

    /**
     * @Transition("viewProductFromHome")
     *
     * @throws Exception
     */
    public function viewProductFromHome(Data $data)
    {
        $product = DataHelper::get($data, 'product', [$this, 'randomProductFromHome'], [$this, 'validateProductFromHome']);
        $this->product = $product;
        $this->category = null;
        $this->goToProduct($product);
    }

    /**
     * @Transition("viewProductFromCart")
     *
     * @throws Exception
     */
    public function viewProductFromCart(Data $data)
    {
        $product = DataHelper::get($data, 'product', [$this, 'randomProductFromCart'], [$this, 'validateProductFromCart']);
        $this->product = $product;
        $this->category = null;
        $this->goToProduct($product);
    }

    /**
     * @Transition("viewProductFromCategory")
     *
     * @throws Exception
     */
    public function viewProductFromCategory(Data $data)
    {
        $product = DataHelper::get($data, 'product', [$this, 'randomProductFromCategory'], [$this, 'validateProductFromCategory']);
        $this->product = $product;
        $this->category = null;
        $this->goToProduct($product);
    }

    /**
     * @Transition("viewCartFromHome")
     */
    public function viewCartFromHome()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCart();
    }

    /**
     * @Transition("viewCartFromCategory")
     */
    public function viewCartFromCategory()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCart();
    }

    /**
     * @Transition("viewCartFromProduct")
     */
    public function viewCartFromProduct()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCart();
    }

    /**
     * @Transition("viewCartFromCheckout")
     */
    public function viewCartFromCheckout()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCart();
    }

    /**
     * @Transition("checkoutFromHome")
     */
    public function checkoutFromHome()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCheckout();
    }

    /**
     * @Transition("checkoutFromCategory")
     */
    public function checkoutFromCategory()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCheckout();
    }

    /**
     * @Transition("checkoutFromProduct")
     */
    public function checkoutFromProduct()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCheckout();
    }

    /**
     * @Transition("checkoutFromCart")
     */
    public function checkoutFromCart()
    {
        $this->category = null;
        $this->product = null;
        $this->goToCheckout();
    }

    /**
     * @Transition("backToHomeFromCategory")
     */
    public function backToHomeFromCategory()
    {
        $this->category = null;
        $this->product = null;
        $this->goToHome();
    }

    /**
     * @Transition("backToHomeFromProduct")
     */
    public function backToHomeFromProduct()
    {
        $this->category = null;
        $this->product = null;
        $this->goToHome();
    }

    /**
     * @Transition("backToHomeFromCart")
     */
    public function backToHomeFromCart()
    {
        $this->category = null;
        $this->product = null;
        $this->goToHome();
    }

    /**
     * @Transition("backToHomeFromCheckout")
     */
    public function backToHomeFromCheckout()
    {
        $this->category = null;
        $this->product = null;
        $this->goToHome();
    }

    /**
     * @Transition("addFromHome")
     *
     * @throws Exception
     */
    public function addFromHome(Data $data)
    {
        $product = DataHelper::get($data, 'product', [$this, 'randomProductFromHome'], [$this, 'validateProductFromHome']);
        if (in_array($product, $this->needOptions)) {
            throw new Exception('You need to specify options for this product! Can not add product from home');
        }
        if (!isset($this->cart[$product])) {
            $this->cart[$product] = 1;
        } else {
            ++$this->cart[$product];
        }
    }

    /**
     * @Transition("addFromCategory")
     *
     * @throws Exception
     */
    public function addFromCategory(Data $data)
    {
        $product = DataHelper::get($data, 'product', [$this, 'randomProductFromCategory'], [$this, 'validateProductFromCategory']);
        if (in_array($product, $this->needOptions)) {
            throw new Exception('You need to specify options for this product! Can not add product from category');
        }
        if (!isset($this->cart[$product])) {
            $this->cart[$product] = 1;
        } else {
            ++$this->cart[$product];
        }
        $by = WebDriverBy::cssSelector(CategoryPage::addToCart($product));
        $this->client->wait(1)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
    }

    /**
     * @Transition("addFromProduct")
     *
     * @throws Exception
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function addFromProduct()
    {
        if (in_array($this->product, $this->needOptions)) {
            throw new Exception('You need to specify options for this product! Can not add product from product');
        }
        if (!isset($this->cart[$this->product])) {
            $this->cart[$this->product] = 1;
        } else {
            ++$this->cart[$this->product];
        }
        $by = WebDriverBy::id(ProductPage::$buttonCart);
        $this->client->wait(1)->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
    }

    /**
     * @Transition("remove")
     *
     * @throws Exception
     */
    public function remove(Data $data)
    {
        $product = DataHelper::get($data, 'product', [$this, 'randomProductFromCart'], [$this, 'validateProductFromCart']);
        unset($this->cart[$product]);
        // TODO id is not product id
        $this->client->findElement(WebDriverBy::cssSelector(CartPage::remove($product)))->click();
    }

    /**
     * @Transition("update")
     *
     * @throws Exception
     */
    public function update(Data $data)
    {
        $product = DataHelper::get($data, 'product', [$this, 'randomProductFromCart'], [$this, 'validateProductFromCart']);
        $quantity = rand(1, 99);
        $this->cart[$product] = $quantity;
        // TODO id is not product id
        $this->client->findElement(WebDriverBy::cssSelector(CartPage::quantity($product)))->sendKeys($quantity);
        $this->client->findElement(WebDriverBy::cssSelector(CartPage::quantityUpdate($product)))->click();
    }

    /**
     * @Place("home")
     */
    public function home()
    {
    }

    /**
     * @Place("category")
     */
    public function category()
    {
    }

    /**
     * @Place("product")
     */
    public function product()
    {
    }

    /**
     * @Place("cart")
     */
    public function cart()
    {
    }

    /**
     * @Place("checkout")
     *
     * @throws Exception
     */
    public function checkout()
    {
        foreach ($this->cart as $product => $quantity) {
            if (in_array($product, $this->outOfStock)) {
                throw new Exception('You added an out-of-stock product into cart! Can not checkout');
            }
        }
    }

    public function hasCoupon()
    {
        return true;
    }

    public function hasGiftCertificate()
    {
        return true;
    }

    public function cartHasProducts(): bool
    {
        return !empty($this->cart);
    }

    public function categoryHasProducts(): bool
    {
        $products = $this->productsInCategory[$this->category] ?? [];

        return !empty($products);
    }

    private function goToCategory($id)
    {
        $this->client->get($this->url."/index.php?route=product/category&path=$id");
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

    private function goToCart()
    {
        $this->client->get($this->url.'/index.php?route=checkout/cart');
    }

    private function goToCheckout()
    {
        $this->client->get($this->url.'/index.php?route=checkout/checkout');
    }

    private function goToHome()
    {
        $this->client->get($this->url.'/index.php?route=common/home');
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

    public function randomCategory()
    {
        return $this->categories[array_rand($this->categories)];
    }

    /**
     * @param $category
     *
     * @throws Exception
     */
    public function validateCategory($category)
    {
        if (!in_array($category, $this->categories)) {
            throw new Exception('Selected category is invalid');
        }
    }

    public function randomProductFromHome()
    {
        return $this->featuredProducts[array_rand($this->featuredProducts)];
    }

    /**
     * @param $product
     *
     * @throws Exception
     */
    public function validateProductFromHome($product)
    {
        if (!in_array($product, $this->featuredProducts)) {
            throw new Exception('Selected product is not in featured products list');
        }
    }

    public function randomProductFromCart()
    {
        // This transition need this guard: subject.cartHasProducts()
        return array_rand($this->cart);
    }

    /**
     * @param $product
     *
     * @throws Exception
     */
    public function validateProductFromCart($product)
    {
        if (empty($this->cart[$product])) {
            throw new Exception('Selected product is not in cart');
        }
    }

    public function randomProductFromCategory()
    {
        // This transition need this guard: subject.categoryHasProducts()
        $products = $this->productsInCategory[$this->category] ?? [];

        return $products[array_rand($products)];
    }

    /**
     * @param $product
     *
     * @throws Exception
     */
    public function validateProductFromCategory($product)
    {
        if (!in_array($product, $this->productsInCategory[$this->category])) {
            throw new Exception('Selected product is not in current category');
        }
    }
}
