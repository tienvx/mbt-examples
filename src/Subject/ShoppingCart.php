<?php

namespace App\Subject;

use RemoteWebDriver;
use Tienvx\Bundle\MbtBundle\Subject\Subject;

class ShoppingCart extends Subject
{
    /**
     * @var RemoteWebDriver
     */
    protected $webDriver;

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
    protected $products = [
        '28', // 'HTC Touch HD',
        '40', // 'iPhone',
        '29', // 'Palm Treo Pro',
        '41', // 'iMac',
        '42', // 'Apple Cinema 30',
        '33', // 'Samsung SyncMaster 941BW',
        '30', // 'Canon EOS 5D',
        '31', // 'Nikon D300',
        '43', // 'MacBook',
    ];

    /**
     * @var array
     */
    protected $categories = [
        '24', // 'Phones & PDAs',
        '17', // 'Software',
        '20_27', // 'Mac',
        '25_28', // 'Monitors',
        '33', // 'Cameras',
        '20', // 'Desktops'
    ];

    /**
     * @var array
     */
    protected $productsInCategory = [
        '24' => [
            '28', // 'HTC Touch HD',
            '40', // 'iPhone',
            '29', // 'Palm Treo Pro',
        ],
        '17' => [],
        '20_27' => [
            '41', // 'iMac',
        ],
        '25_28' => [
            '42', // 'Apple Cinema 30',
            '33', // 'Samsung SyncMaster 941BW'
        ],
        '33' => [
            '30', // 'Canon EOS 5D',
            '31', // 'Nikon D300'
        ],
        '20' => [
            '43', // 'MacBook'
        ]
    ];

    /**
     * @var array
     */
    protected $stock = [
        '29', // 'Palm Treo Pro',
        '42', // 'Apple Cinema 30',
        '30', // 'Canon EOS 5D',
        '31', // 'Nikon D300',
        '43', // 'MacBook',
    ];

    public function __construct()
    {
        $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => 'firefox');
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
        $this->cart = [];
        $this->category = null;
        $this->product = null;
        parent::__construct();
    }

    public function tearDown()
    {
        $this->webDriver->quit();
    }

    /**
     * @param $data array
     */
    public function viewAnyCategoryFromHome($data)
    {
        $category = $data['category'];
        $this->category = $category;
        $this->product = null;
    }

    /**
     * @param $data array
     */
    public function viewOtherCategory($data)
    {
        $category = $data['category'];
        $this->category = $category;
        $this->product = null;
    }

    /**
     * @param $data array
     */
    public function viewAnyCategoryFromProduct($data)
    {
        $category = $data['category'];
        $this->category = $category;
        $this->product = null;
    }

    /**
     * @param $data array
     */
    public function viewAnyCategoryFromCart($data)
    {
        $category = $data['category'];
        $this->category = $category;
        $this->product = null;
    }

    /**
     * @param $data array
     */
    public function viewProductFromHome($data)
    {
        $product = $data['product'];
        $this->product = $product;
        $this->category = null;
    }

    /**
     * @param $data array
     */
    public function viewProductFromCart($data)
    {
        $product = $data['product'];
        $this->product = $product;
        $this->category = null;
    }

    /**
     * @param $data array
     */
    public function viewProductFromCategory($data)
    {
        $product = $data['product'];
        $this->product = $product;
        $this->category = null;
    }

    public function categoryHasProduct($data)
    {
        if (!isset($data['product'])) {
            return false;
        }
        $product = $data['product'];
        return !empty($this->productsInCategory[$this->category]) &&
            in_array($product, $this->productsInCategory[$this->category]);
    }

    public function viewCartFromHome()
    {
        $this->category = null;
        $this->product = null;
    }

    public function viewCartFromCategory()
    {
        $this->category = null;
        $this->product = null;
    }

    public function viewCartFromProduct()
    {
        $this->category = null;
        $this->product = null;
    }

    public function viewCartFromCheckout()
    {
        $this->category = null;
        $this->product = null;
    }

    public function checkoutFromHome()
    {
        $this->category = null;
        $this->product = null;
    }

    public function checkoutFromCategory()
    {
        $this->category = null;
        $this->product = null;
    }

    public function checkoutFromProduct()
    {
        $this->category = null;
        $this->product = null;
    }

    public function checkoutFromCart()
    {
        $this->category = null;
        $this->product = null;
    }

    public function backToHomeFromCategory()
    {
        $this->category = null;
        $this->product = null;
    }

    public function backToHomeFromProduct()
    {
        $this->category = null;
        $this->product = null;
    }

    public function backToHomeFromCart()
    {
        $this->category = null;
        $this->product = null;
    }

    public function backToHomeFromCheckout()
    {
        $this->category = null;
        $this->product = null;
    }

    public function cartHasProduct($data)
    {
        if (!isset($data['product'])) {
            return false;
        }
        $product = $data['product'];
        return !empty($this->cart[$product]);
    }

    /**
     * @param $data array
     */
    public function addFromHome($data)
    {
        $product = $data['product'];
        if (!isset($this->cart[$product])) {
            $this->cart[$product] = 1;
        }
        else {
            $this->cart[$product]++;
        }
    }

    /**
     * @param $data array
     */
    public function addFromCategory($data)
    {
        $product = $data['product'];
        if (!isset($this->cart[$product])) {
            $this->cart[$product] = 1;
        }
        else {
            $this->cart[$product]++;
        }
    }

    public function addFromProduct()
    {
        if (!isset($this->cart[$this->product])) {
            $this->cart[$this->product] = 1;
        }
        else {
            $this->cart[$this->product]++;
        }
    }

    /**
     * @param $data array
     */
    public function remove($data)
    {
        $product = $data['product'];
        unset($this->cart[$product]);
    }

    /**
     * @param $data array
     * @throws \Exception
     */
    public function update($data)
    {
        $product = $data['product'];
        $this->cart[$product] = rand(1, 99);
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

    public function checkout()
    {
        if ($this->callSUT) {
            foreach ($this->cart as $product => $quantity) {
                if (!in_array($product, $this->stock)) {
                    throw new \Exception('You added an out-of-stock product into cart! Can not checkout');
                }
            }
        }
    }

    public function getRandomProduct()
    {
        if (empty($this->products)) {
            return null;
        }
        $product = $this->products[array_rand($this->products)];
        return $product;
    }

    public function getRandomCategory()
    {
        if (empty($this->categories)) {
            return null;
        }
        $category = $this->categories[array_rand($this->categories)];
        return $category;
    }

    public function getRandomProductFromCart()
    {
        if (empty($this->cart)) {
            return null;
        }
        $product = array_rand($this->cart);
        return $product;
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
        return $product;
    }
}
