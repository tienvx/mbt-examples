<?php

namespace App\Subject;

use App\Helper\ElementHelper;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Exception\UnexpectedTagNameException;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
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
        $this->client->quit();
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

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function addToCart()
    {
        $by = WebDriverBy::id('button-cart');
        $this->waitAndClick($by);
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function addToWishList()
    {
        $by = WebDriverBy::xpath("//button[@data-original-title='Add to Wish List']");
        $this->waitAndClick($by);
        $this->closeAlerts();
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function compareThisProduct()
    {
        $by = WebDriverBy::xpath("//button[@data-original-title='Compare this Product']");
        $this->waitAndClick($by);
        $this->closeAlerts();
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function writeAReview()
    {
        $by = WebDriverBy::linkText('Write a review');
        $this->waitAndClick($by);
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function fillName()
    {
        $by = WebDriverBy::id('input-name');
        $element = $this->waitAndClick($by);
        $element->sendKeys('My Name');
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function fillReview()
    {
        $by = WebDriverBy::id('input-review');
        $element = $this->waitAndClick($by);
        $element->sendKeys('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut et rutrum sem, at lacinia orci. Suspendisse eget posuere odio, a venenatis libero. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed mauris dui, congue et tellus at, pharetra bibendum diam. Donec diam justo, aliquam quis massa vel, cursus commodo odio. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In tempus mi sit amet semper imperdiet. Maecenas mollis nisi nulla, at viverra sapien auctor vel. Phasellus tincidunt, dolor et eleifend pretium, nulla magna malesuada nisi, id hendrerit mi orci eget sapien. Proin venenatis aliquet elit eu eleifend. In leo massa, convallis a felis eget, malesuada sagittis ipsum.');
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
        $by = WebDriverBy::xpath("//input[@name='rating' and @value='{$rating}']");
        $this->waitAndClick($by);
    }

    public function getRandomRating()
    {
        return ['rating' => rand(1, 5)];
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function submitReview()
    {
        $by = WebDriverBy::id('button-review');
        $this->waitAndClick($by);
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

    public function captureScreenshot($bugId, $index)
    {
        if (!is_dir($this->screenshotsDir . "/{$bugId}")) {
            mkdir($this->screenshotsDir . "/{$bugId}", 0777, true);
        }
        $this->client->takeScreenshot($this->screenshotsDir . "/{$bugId}/{$index}.png");
    }

    /**
     * @param WebDriverBy $by
     * @return WebDriverElement
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function waitAndClick(WebDriverBy $by): WebDriverElement
    {
        $this->client->wait()->until(
            WebDriverExpectedCondition::elementToBeClickable($by)
        );
        $element = $this->client->findElement($by);
        $element->click();
        return $element;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function closeAlerts()
    {
        $this->client->waitFor('.alert');
        /** @var WebDriverElement[] $elements */
        $elements = $this->client->findElements(WebDriverBy::cssSelector('.alert > .close'));
        foreach ($elements as $element) {
            $element->click();
        }
    }
}
