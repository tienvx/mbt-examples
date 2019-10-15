<?php

namespace App\Subject;

use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Exception\UnexpectedTagNameException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverSelect;
use Symfony\Component\Process\Process;
use App\Helper\ElementHelper;
use Tienvx\Bundle\MbtBundle\Annotation\Subject;
use Tienvx\Bundle\MbtBundle\Annotation\Transition;
use Tienvx\Bundle\MbtBundle\Entity\Data;
use Tienvx\Bundle\MbtBundle\Subject\AbstractSubject;
use App\Helper\SetUp;
use App\PageObjects\MasterPage;
use App\PageObjects\ProductPage;

/**
 * @Subject("product")
 */
class Product extends AbstractSubject
{
    use ElementHelper;
    use SetUp;

    /**
     * @var int
     */
    protected $productId = 42;

    /**
     * @var string
     */
    protected $url = 'http://example.com';

    /**
     * @param bool $testing
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function setUp(bool $testing = false)
    {
        if ($testing) {
            $this->url = 'https://demo.opencart.com';
        }
        $this->firefox($testing);
        $this->goToProduct($this->productId);
    }

    public function tearDown()
    {
        $this->client->quit();
    }

    /**
     * @Transition("selectRadio")
     *
     * @param Data $data
     *
     * @throws Exception
     */
    public function selectRadio(Data $data)
    {
        if ($data->has('radio')) {
            $radio = $data->get('radio');
            if (!in_array($radio, range(5, 7))) {
                throw new Exception('Selected radio is invalid');
            }
        } else {
            $radio = rand(5, 7);
            $data->set('radio', $radio);
        }
        $this->client->findElement(WebDriverBy::xpath(ProductPage::radio($radio)))->click();
    }

    /**
     * @Transition("selectCheckbox")
     *
     * @param Data $data
     *
     * @throws Exception
     */
    public function selectCheckbox(Data $data)
    {
        if ($data->has('checkbox')) {
            $checkbox = $data->get('checkbox');
            if (!in_array($checkbox, range(8, 11))) {
                throw new Exception('Selected checkbox is invalid');
            }
        } else {
            $checkbox = rand(8, 11);
            $data->set('checkbox', $checkbox);
        }
        $this->client->findElement(WebDriverBy::xpath(ProductPage::checkbox($checkbox)))->click();
    }

    /**
     * @Transition("fillText")
     */
    public function fillText()
    {
        $this->client->findElement(WebDriverBy::id(ProductPage::$textbox))->sendKeys('Test text');
    }

    /**
     * @Transition("selectSelect")
     *
     * @param Data $data
     *
     * @throws Exception
     * @throws NoSuchElementException
     * @throws UnexpectedTagNameException
     */
    public function selectSelect(Data $data)
    {
        if ($data->has('select')) {
            $select = $data->get('select');
            if (!in_array($select, range(1, 4))) {
                throw new Exception('Selected select is invalid');
            }
        } else {
            $select = rand(1, 4);
            $data->set('select', $select);
        }
        $regionElement = $this->client->findElement(WebDriverBy::id(ProductPage::$selectbox));
        $region = new WebDriverSelect($regionElement);
        $region->selectByValue($select);
    }

    /**
     * @Transition("fillTextarea")
     */
    public function fillTextarea()
    {
        $this->client->findElement(WebDriverBy::id(ProductPage::$textarea))->sendKeys('Test textarea');
    }

    /**
     * @Transition("selectFile")
     *
     * @throws Exception
     */
    public function selectFile()
    {
        throw new Exception('Can not upload file!');
    }

    /**
     * @Transition("selectDate")
     */
    public function selectDate()
    {
    }

    /**
     * @Transition("selectTime")
     */
    public function selectTime()
    {
    }

    /**
     * @Transition("selectDateTime")
     */
    public function selectDateTime()
    {
    }

    /**
     * @Transition("addToCart")
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function addToCart()
    {
        $by = WebDriverBy::id(ProductPage::$buttonCart);
        $this->waitAndClick($by);
    }

    /**
     * @Transition("addToWishList")
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function addToWishList()
    {
        $by = WebDriverBy::xpath(ProductPage::$buttonAddToWishlist);
        $this->waitAndClick($by);
        $this->closeAlerts();
    }

    /**
     * @Transition("compareThisProduct")
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function compareThisProduct()
    {
        $by = WebDriverBy::xpath(ProductPage::$buttonCompareThisProduct);
        $this->waitAndClick($by);
        $this->closeAlerts();
    }

    /**
     * @Transition("writeAReview")
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function writeAReview()
    {
        $by = WebDriverBy::linkText(ProductPage::$linkWriteAReview);
        $this->waitAndClick($by);
    }

    /**
     * @Transition("fillName")
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function fillName()
    {
        $by = WebDriverBy::id(ProductPage::$inputName);
        $element = $this->waitAndClick($by);
        $element->sendKeys('My Name');
    }

    /**
     * @Transition("fillReview")
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function fillReview()
    {
        $by = WebDriverBy::id(ProductPage::$inputReview);
        $element = $this->waitAndClick($by);
        $element->sendKeys('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut et rutrum sem, at lacinia orci. Suspendisse eget posuere odio, a venenatis libero. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed mauris dui, congue et tellus at, pharetra bibendum diam. Donec diam justo, aliquam quis massa vel, cursus commodo odio. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In tempus mi sit amet semper imperdiet. Maecenas mollis nisi nulla, at viverra sapien auctor vel. Phasellus tincidunt, dolor et eleifend pretium, nulla magna malesuada nisi, id hendrerit mi orci eget sapien. Proin venenatis aliquet elit eu eleifend. In leo massa, convallis a felis eget, malesuada sagittis ipsum.');
    }

    /**
     * @Transition("fillRating")
     *
     * @param Data $data
     *
     * @throws Exception
     */
    public function fillRating(Data $data)
    {
        if ($data->has('rating')) {
            $rating = $data->get('rating');
            if (!in_array($rating, range(1, 5))) {
                throw new Exception('Selected rating is invalid');
            }
        } else {
            $rating = rand(1, 5);
            $data->set('rating', $rating);
        }
        $by = WebDriverBy::xpath(ProductPage::rating($rating));
        $this->waitAndClick($by);
    }

    /**
     * @Transition("submitReview")
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function submitReview()
    {
        $by = WebDriverBy::id(ProductPage::$buttonReview);
        $this->waitAndClick($by);
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

    public function captureScreenshot($bugId, $index)
    {
        $this->client->takeScreenshot('/tmp/screenshot.png');

        $process = Process::fromShellCommandline('pngquant --quality=60-90 - < /tmp/screenshot.png');
        $process->run();

        $image = $process->getOutput();
        $this->filesystem->put("{$bugId}/{$index}.png", $image);

        unlink('/tmp/screenshot.png');
    }

    /**
     * @param WebDriverBy $by
     *
     * @return WebDriverElement
     *
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function waitAndClick(WebDriverBy $by): WebDriverElement
    {
        $this->client->wait(1)->until(
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
        $this->client->waitFor(MasterPage::$alert, 1);
        /** @var WebDriverElement[] $elements */
        $elements = $this->client->findElements(WebDriverBy::cssSelector(MasterPage::$closeAlert));
        foreach ($elements as $element) {
            $element->click();
        }
    }

    public function getScreenshotUrl($bugId, $index)
    {
        return sprintf('http://localhost/api/bugs/%d/screenshot/%d', $bugId, $index);
    }
}
