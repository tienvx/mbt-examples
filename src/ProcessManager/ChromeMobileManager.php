<?php

namespace App\ProcessManager;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriver;
use ReflectionClass;
use Symfony\Component\Panther\ProcessManager\BrowserManagerInterface;
use Symfony\Component\Panther\ProcessManager\ChromeManager;
use Symfony\Component\Panther\ProcessManager\WebServerReadinessProbeTrait;
use Symfony\Component\Process\Process;

/**
 * @see \Symfony\Component\Panther\ProcessManager\ChromeManager
 */
final class ChromeMobileManager implements BrowserManagerInterface
{
    use WebServerReadinessProbeTrait;

    private $process;
    private $options;

    public function __construct(?string $chromeDriverBinary = null, array $options = [])
    {
        $this->options = array_merge($this->getDefaultOptions(), $options);
        $this->process = new Process([$chromeDriverBinary ?: $this->findChromeDriverBinary(), '--port='.$this->options['port']], null, null, null, null);
    }

    /**
     * @throws \RuntimeException
     */
    public function start(): WebDriver
    {
        $url = $this->options['scheme'].'://'.$this->options['host'].':'.$this->options['port'];
        if (!$this->process->isRunning()) {
            $this->checkPortAvailable($this->options['host'], $this->options['port']);
            $this->process->start();
            $this->waitUntilReady($this->process, $url.$this->options['path'], 'chrome');
        }

        $capabilities = DesiredCapabilities::chrome();
        $chromeOptions = new ChromeOptions();
        $chromeOptions->setExperimentalOption('mobileEmulation', [
            'deviceName' => 'Pixel 2',
        ]);
        if (isset($_SERVER['PANTHER_CHROME_BINARY'])) {
            $chromeOptions->setBinary($_SERVER['PANTHER_CHROME_BINARY']);
        }
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

        return RemoteWebDriver::create($url, $capabilities);
    }

    public function quit(): void
    {
        $this->process->stop();
    }

    private function findChromeDriverBinary(): string
    {
        if ($binary = $_SERVER['PANTHER_CHROME_DRIVER_BINARY'] ?? null) {
            return $binary;
        }

        $reflection = new ReflectionClass(ChromeManager::class);
        $dir = dirname($reflection->getFileName());
        switch (PHP_OS_FAMILY) {
            case 'Windows':
                return $dir.'/../../chromedriver-bin/chromedriver.exe';
            case 'Darwin':
                return $dir.'/../../chromedriver-bin/chromedriver_mac64';
            default:
                return $dir.'/../../chromedriver-bin/chromedriver_linux64';
        }
    }

    private function getDefaultOptions(): array
    {
        return [
            'scheme' => 'http',
            'host' => '127.0.0.1',
            'port' => 9515,
            'path' => '/status',
        ];
    }
}
