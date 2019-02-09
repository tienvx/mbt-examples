<?php

namespace App\Subject;

use App\Helper\ElementHelper;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Exception\UnexpectedTagNameException;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverSelect;
use Symfony\Component\Panther\Client;
use Tienvx\Bundle\MbtBundle\Annotation\DataProvider;
use Tienvx\Bundle\MbtBundle\Subject\AbstractSubject;

class Product extends AbstractSubject
{
    use ElementHelper;

    /**
     * @var int
     */
    protected $productId = 42;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string $url
     */
    protected $url = 'http://example.com';

    public static function support(): string
    {
        return 'product';
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function setUp()
    {
        $this->client = Client::createChromeClient();
        $this->goToProduct($this->productId);
    }

    public function tearDown()
    {
    }

    /**
     * @DataProvider(method="getRandomRadio")
     * @throws Exception
     */
    public function selectRadio()
    {
        if (empty($this->data['radio'])) {
            throw new Exception('Can not select radio: random option is not chosen');
        }
        $radio = $this->data['radio'];
        $this->client->findElement(WebDriverBy::xpath("//input[@name='option[218]' and @value='{$radio}']"))->click();
    }

    public function getRandomRadio()
    {
        return ['radio' => rand(5, 7)];
    }

    /**
     * @DataProvider(method="getRandomCheckbox")
     * @throws Exception
     */
    public function selectCheckbox()
    {
        if (empty($this->data['checkbox'])) {
            throw new Exception('Can not select checkbox: random option is not chosen');
        }
        $checkbox = $this->data['checkbox'];
        $this->client->findElement(WebDriverBy::xpath("//input[@name='option[223][]' and @value='{$checkbox}']"))->click();
    }

    public function getRandomCheckbox()
    {
        // Can update code to select more than 1 checkbox
        return ['checkbox' => rand(8, 11)];
    }

    public function fillText()
    {
        $this->client->findElement(WebDriverBy::id('input-option208'))->sendKeys('Test text');
    }

    /**
     * @DataProvider(method="getRandomSelect")
     * @throws Exception
     * @throws NoSuchElementException
     * @throws UnexpectedTagNameException
     */
    public function selectSelect()
    {
        if (empty($this->data['select'])) {
            throw new Exception('Can not select dropdown: random option is not chosen');
        }
        $checkbox = $this->data['select'];
        $regionElement = $this->client->findElement(WebDriverBy::id('input-option217'));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue($checkbox);
    }

    public function getRandomSelect()
    {
        return ['select' => rand(1, 4)];
    }

    public function fillTextarea()
    {
        $this->client->findElement(WebDriverBy::id('input-option209'))->sendKeys('Test textarea');
    }

    /**
     * @throws Exception
     */
    public function selectFile()
    {
        //$fileInput = $this->client->getWebDriver()->findElement(WebDriverBy::id('input-option222'));
        $fileInput = $this->client->wait()->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('input-option222'))
        );

        // set the file detector
        $fileInput->setFileDetector(new LocalFileDetector());

        // create temporary file
        $file = tmpfile();
        $filePath = stream_get_meta_data($file)['uri'];

        // upload the file and submit the form
        $fileInput->sendKeys($filePath)->submit();

        $this->client->wait()->until(
            WebDriverExpectedCondition::not(
                WebDriverExpectedCondition::elementTextContains(WebDriverBy::id('input-option222'), 'Loading...')
            )
        );

        if (!$this->testing) {
            $text = $this->client->getWebDriver()->findElement(WebDriverBy::id('product'))->getText();
            if (strpos($text, 'Upload required!') !== false) {
                throw new Exception('Upload required!');
            }
        }
    }

    public function selectDate() {}

    public function selectTime() {}

    public function selectDateTime() {}

    public function addToCart()
    {
        $this->client->findElement(WebDriverBy::id('button-cart'))->click();
    }

    public function addToWishList()
    {
        $this->client->findElement(WebDriverBy::xpath("//button[@data-original-title='Add to Wish List']"))->click();
    }

    public function compareThisProduct()
    {
        $this->client->findElement(WebDriverBy::xpath("//button[@data-original-title='Compare this Product']"))->click();
    }

    public function writeAReview()
    {
        $this->client->findElement(WebDriverBy::linkText('Write a review'))->click();
    }

    public function fillName()
    {
        $this->client->findElement(WebDriverBy::id('input-name'))->sendKeys('My Name');
    }

    public function fillReview()
    {
        $this->client->findElement(WebDriverBy::id('input-review'))->sendKeys('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut et rutrum sem, at lacinia orci. Suspendisse eget posuere odio, a venenatis libero. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed mauris dui, congue et tellus at, pharetra bibendum diam. Donec diam justo, aliquam quis massa vel, cursus commodo odio. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In tempus mi sit amet semper imperdiet. Maecenas mollis nisi nulla, at viverra sapien auctor vel. Phasellus tincidunt, dolor et eleifend pretium, nulla magna malesuada nisi, id hendrerit mi orci eget sapien. Proin venenatis aliquet elit eu eleifend. In leo massa, convallis a felis eget, malesuada sagittis ipsum.');
    }

    /**
     * @DataProvider(method="getRandomRating")
     * @throws Exception
     */
    public function fillRating()
    {
        if (empty($this->data['rating'])) {
            throw new Exception('Can not select rating: random option is not chosen');
        }
        $rating = $this->data['rating'];
        $this->client->findElement(WebDriverBy::xpath("//input[@name='rating' and @value='{$rating}']"))->click();
    }

    public function getRandomRating()
    {
        return ['rating' => rand(1, 5)];
    }

    public function submitReview()
    {
        $this->client->findElement(WebDriverBy::id('button-review'))->click();
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
}
