<?php

namespace App\Helper;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\StaleElementReferenceException;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\Client;

trait ElementHelper
{
    /**
     * @return bool
     */
    public function hasElement(Client $client, WebDriverBy $by)
    {
        try {
            return $client->findElement($by)->isDisplayed();
        } catch (NoSuchElementException $e) {
            return false;
        } catch (StaleElementReferenceException $e) {
            return false;
        }
    }
}
