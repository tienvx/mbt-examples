<?php

namespace App\Helper;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\StaleElementReferenceException;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

trait ElementHelper
{
    public function hasElement(RemoteWebDriver $driver, WebDriverBy $by)
    {
        try {
            return $driver->findElement($by)->isDisplayed();
        } catch (NoSuchElementException $e) {
            return false;
        } catch (StaleElementReferenceException $e) {
            return false;
        }
    }
}
