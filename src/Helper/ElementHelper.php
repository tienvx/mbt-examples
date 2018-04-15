<?php

namespace App\Helper;

use RemoteWebDriver;
use WebDriverBy;
use NoSuchElementException;
use StaleElementReferenceException;

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