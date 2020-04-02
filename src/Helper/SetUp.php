<?php

namespace App\Helper;

use App\ProcessManager\ChromeMobileManager;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Symfony\Component\Panther\Client;

trait SetUp
{
    /**
     * @var Client
     */
    protected $client;

    public function firefox(bool $testing)
    {
        if ($testing) {
            // TODO open local firefox (currently using w3c protocol, not json wire)
            $this->client = Client::createChromeClient();
            $this->client->manage()->window()->maximize();
        } else {
            $caps = new DesiredCapabilities();
            $caps->setCapability(WebDriverCapabilityType::BROWSER_NAME, WebDriverBrowserType::FIREFOX);
            $caps->setCapability(WebDriverCapabilityType::VERSION, '74.0');
            // These capabilities are for Selenoid only
            $caps->setCapability('enableVNC', true);
            $caps->setCapability('enableLog', false);
            $caps->setCapability('enableVideo', false);
            $this->client = Client::createSeleniumClient('http://hub:4444/wd/hub', $caps);
        }
    }

    public function chrome(bool $testing)
    {
        if ($testing) {
            $this->client = Client::createChromeClient();
            $this->client->manage()->window()->maximize();
        } else {
            $caps = new DesiredCapabilities();
            $caps->setCapability(WebDriverCapabilityType::BROWSER_NAME, WebDriverBrowserType::CHROME);
            $caps->setCapability(WebDriverCapabilityType::VERSION, '80.0');
            // These capabilities are for Selenoid only
            $caps->setCapability('enableVNC', true);
            $caps->setCapability('enableLog', false);
            $caps->setCapability('enableVideo', false);
            $this->client = Client::createSeleniumClient('http://hub:4444/wd/hub', $caps);
        }
    }

    public function opera(bool $testing)
    {
        if ($testing) {
            // TODO open local opera
            $this->client = Client::createChromeClient();
            $this->client->manage()->window()->maximize();
        } else {
            $caps = new DesiredCapabilities();
            $caps->setCapability(WebDriverCapabilityType::BROWSER_NAME, WebDriverBrowserType::OPERA);
            $caps->setCapability(WebDriverCapabilityType::VERSION, '67.0');
            // These capabilities are for Selenoid only
            $caps->setCapability('enableVNC', true);
            $caps->setCapability('enableLog', false);
            $caps->setCapability('enableVideo', false);
            $this->client = Client::createSeleniumClient('http://hub:4444/wd/hub', $caps);
        }
    }

    public function android(bool $testing)
    {
        if ($testing) {
            $this->client = new Client(new ChromeMobileManager());
        } else {
            $caps = new DesiredCapabilities();
            $caps->setCapability(WebDriverCapabilityType::BROWSER_NAME, WebDriverBrowserType::CHROME);
            $caps->setCapability(WebDriverCapabilityType::VERSION, '79.0');
            $caps->setCapability('selenoid:options', [
                WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::ANDROID,
                'skin' => 'WXGA720',
            ]);
            // These capabilities are for Selenoid only
            $caps->setCapability('enableVNC', true);
            $caps->setCapability('enableLog', false);
            $caps->setCapability('enableVideo', false);
            $this->client = Client::createSeleniumClient('http://hub:4444/wd/hub', $caps);
        }
    }
}
