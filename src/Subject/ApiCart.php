<?php

namespace App\Subject;

use Exception;
use League\Flysystem\FileNotFoundException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Tienvx\Bundle\MbtBundle\Annotation\Subject;
use Tienvx\Bundle\MbtBundle\Annotation\Transition;
use Tienvx\Bundle\MbtBundle\Steps\Data;
use Tienvx\Bundle\MbtBundle\Steps\DataHelper;
use Tienvx\Bundle\MbtBundle\Subject\AbstractSubject;
use Webmozart\Assert\Assert;

/**
 * @Subject("api_cart")
 */
class ApiCart extends AbstractSubject
{
    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiToken;

    /**
     * @var array
     */
    protected $body = [];

    /**
     * @var string
     */
    protected $url = 'http://example.com';

    /**
     * @var array
     */
    protected $cart;

    /**
     * @var array
     */
    protected $products = [
        //'20' => [
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
        //],
        //'20_27' => [
            '41', // 'iMac',
        //],
        //'18' => [
            '47', // 'HP LP3065',
            '43', // 'MacBook',
            '44', // 'MacBook Air',
            '45', // 'MacBook Pro',
            '46', // 'Sony VAIO',
        //],
        //'25' => [],
        //'25_28' => [
            '42', // 'Apple Cinema 30',
            '33', // 'Samsung SyncMaster 941BW'
        //],
        //'57' => [
            '49', // 'Samsung Galaxy Tab 10.1',
        //],
        //'17' => [],
        //'24' => [
            '28', // 'HTC Touch HD',
            '40', // 'iPhone',
            '29', // 'Palm Treo Pro',
        //],
        //'33' => [
            '30', // 'Canon EOS 5D',
            '31', // 'Nikon D300'
        //],
        //'34' => [
            '48', // 'iPod Classic',
            '36', // 'iPod Nano',
            '34', // 'iPod Shuffle',
            '32', // 'iPod Touch',
        //],
    ];

    public function __construct()
    {
        $this->cart = [];
    }

    public function setUp(bool $testing = false): void
    {
        if ($testing) {
            $this->url = 'https://demo.opencart.com';
        }
        $this->client = HttpClient::create();
    }

    public function tearDown(): void
    {
        $this->client = null;
    }

    /**
     * @Transition("login")
     *
     * @throws ExceptionInterface
     */
    public function login()
    {
        $response = $this->client->request('POST', $this->url.'/index.php?route=api/login', [
            'body' => [
                'username' => 'admin',
                'key' => 'admin',
            ],
        ]);
        Assert::eq(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        Assert::keyExists($body, 'api_token');
        $this->apiToken = $body['api_token'];
    }

    /**
     * @Transition("products")
     *
     * @throws ExceptionInterface
     */
    public function products()
    {
        $response = $this->client->request('POST', $this->url.'/index.php?route=api/cart/products&api_token='.$this->apiToken, [
            'body' => [],
        ]);
        Assert::eq(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);

        return $body;
    }

    /**
     * @Transition("edit")
     *
     * @param Data $data
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function edit(Data $data)
    {
        $product = DataHelper::get($data, 'product', [$this, 'randomProductFromCart'], [$this, 'validateProductFromCart']);
        $quantity = rand(1, 9);
        $response = $this->client->request('POST', $this->url.'/index.php?route=api/cart/edit&api_token='.$this->apiToken, [
            'body' => [
                'key' => $product,
                'quantity' => $quantity,
            ],
        ]);
        Assert::eq(200, $response->getStatusCode());
        $this->cart[$product] = $quantity;
    }

    /**
     * @Transition("remove")
     *
     * @param Data $data
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function remove(Data $data)
    {
        $product = DataHelper::get($data, 'product', [$this, 'randomProductFromCart'], [$this, 'validateProductFromCart']);
        $response = $this->client->request('POST', $this->url.'/index.php?route=api/cart/remove&api_token='.$this->apiToken, [
            'body' => [
                'key' => $product,
            ],
        ]);
        Assert::eq(200, $response->getStatusCode());
        unset($this->cart[$product]);
    }

    /**
     * @Transition("add")
     *
     * @param Data $data
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function add(Data $data)
    {
        $product = DataHelper::get($data, 'product', [$this, 'randomProductNotInCart'], [$this, 'validateProductNotInCart']);
        $response = $this->client->request('POST', $this->url.'/index.php?route=api/cart/remove&api_token='.$this->apiToken, [
            'body' => [
                'key' => $product,
            ],
        ]);
        Assert::eq(200, $response->getStatusCode());
        $this->cart[$product] = 1;
    }

    public function hasApiToken(): bool
    {
        return !empty($this->apiToken);
    }

    public function cartHasProducts(): bool
    {
        return !empty($this->cart);
    }

    /**
     * @throws ExceptionInterface
     */
    public function captureScreenshot($bugId, $index): void
    {
        $text = json_encode($this->products());
        $this->filesystem->put("{$bugId}/{$index}.txt", $text);
    }

    public function isImageScreenshot(): bool
    {
        return false;
    }

    public function hasScreenshot($bugId, $index): bool
    {
        return $this->filesystem->has("{$bugId}/{$index}.txt");
    }

    public function getScreenshot($bugId, $index): string
    {
        try {
            return $this->filesystem->read("{$bugId}/{$index}.txt");
        } catch (FileNotFoundException $e) {
            return '';
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

    public function randomProductNotInCart()
    {
        // Random product not in cart
        do {
            $product = $this->products[array_rand($this->products)];
        } while (isset($this->cart[$product]));

        return $product;
    }

    /**
     * @param $product
     *
     * @throws Exception
     */
    public function validateProductNotInCart($product)
    {
        if (!in_array($product, $this->products) || isset($this->cart[$product])) {
            throw new Exception('Selected product is invalid, or already in cart');
        }
    }
}
