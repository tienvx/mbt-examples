<?php

namespace App\Helper;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

trait ElementHelper
{
    public function hasElement(RemoteWebDriver $driver, WebDriverBy $by)
    {
        return $driver->findElement($by)->isDisplayed();
    }
}
