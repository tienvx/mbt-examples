<?php

namespace Tienvx\Bundle\MbtExamplesBundle\Tests;

use App\Subject\ApiCart;
use App\Subject\Checkout;
use App\Subject\MobileHome;
use App\Subject\Product;
use App\Subject\ShoppingCart;
use Exception;
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
        $this->assertInstanceOf(Checkout::class, $this->subjectManager->create('checkout'));
        $this->assertInstanceOf(Product::class, $this->subjectManager->create('product'));
        $this->assertInstanceOf(ShoppingCart::class, $this->subjectManager->create('shopping_cart'));
        $this->assertInstanceOf(ApiCart::class, $this->subjectManager->create('api_cart'));
        $this->assertInstanceOf(MobileHome::class, $this->subjectManager->create('mobile_home'));
    }
}
