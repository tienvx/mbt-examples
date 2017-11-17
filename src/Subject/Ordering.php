<?php

namespace App\Subject;

use Tienvx\Bundle\MbtBundle\Subject\Subject;

class Ordering extends Subject
{
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
    /* Ordering */
    protected $orders= [
        '1840', // 'Mithun Deshmukh',
        '1842', // 'fred evenfred',
        '1843', // 'Order's johan garzon',
        '1844', // 'ddd ddd',
        '1845', // 'Order's Auto QA ',
        '1846', // 'Order's johan garzon',
        '1847', // 'Order's johan garzon ',

    ];

    public function __construct()
    {
        $this->cart = [];
        $this->category = null;
        $this->product = null;
        $this->order =null;
        parent::__construct();
    }
    public function viewProductfromHomePage($data)
    {
        $product = $data['product'];
        $this->product = $product;
        $this->category = null;
    }
    public function HomeHasProduct()
    {
        if (!isset($data['product'])) {
            return false;
        }
        $product = $data['product'];

    }
}
