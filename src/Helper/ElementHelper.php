<?php

namespace App\Helper;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\StaleElementReferenceException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Symfony\Component\Panther\Client;

trait ElementHelper
{
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


    /**
     * @throws NoSuchElementException
     * @throws TimeOutException
     */
    public function waitFor(Client $client, WebDriverBy $by)
    {
        $client->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                $by
            )
        );
    }
}
