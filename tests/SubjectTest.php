<?php

namespace Tienvx\Bundle\MbtExamplesBundle\Tests;

use App\Subject\ApiCart;
use App\Subject\Checkout;
use App\Subject\MobileHome;
use App\Subject\Product;
use App\Subject\ShoppingCart;
use Exception;
use ReflectionObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tienvx\Bundle\MbtBundle\Subject\SubjectManager;

class SubjectTest extends KernelTestCase
{
    /**
     * @var SubjectManager
     */
    protected $subjectManager;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        static::bootKernel();

        $this->subjectManager = self::$container->get(SubjectManager::class);
    }

    /**
     * @throws Exception
     */
    public function testSubjects(): void
    {
        $reflection = new ReflectionObject($this->subjectManager);
        $property = $reflection->getProperty('subjects');
        $property->setAccessible(true);
        $this->assertEquals([
            'checkout' => Checkout::class,
            'product' => Product::class,
            'shopping_cart' => ShoppingCart::class,
            'api_cart' => ApiCart::class,
            'mobile_home' => MobileHome::class,
        ], $property->getValue($this->subjectManager));
    }
}
