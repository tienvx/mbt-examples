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
    public function testSubjects()
    {
        $this->assertInstanceOf(Checkout::class, $this->subjectManager->createSubject('checkout'));
        $this->assertInstanceOf(Product::class, $this->subjectManager->createSubject('product'));
        $this->assertInstanceOf(ShoppingCart::class, $this->subjectManager->createSubject('shopping_cart'));
        $this->assertInstanceOf(ApiCart::class, $this->subjectManager->createSubject('api_cart'));
        $this->assertInstanceOf(MobileHome::class, $this->subjectManager->createSubject('mobile_home'));
    }
}
