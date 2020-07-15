<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Provider\Type;


use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\DashboardBundle\Provider\Type\DashboardProviderType;
use Enhavo\Bundle\DashboardBundle\Provider\Type\RepositoryDashboardProviderType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepositoryDashboardProviderTypeTest extends TestCase
{
    private $instance;

    private $testCount = 789;
    private $testCountMethod = 'findAll';
    private $parentOptionsKey = 'test_key';
    private $parentOptionsValue = 'test_value';

    public function setUp()
    {
        $this->instance = $this->createInstance();
        $this->instance->setParent($this->createParentMock());
    }

    public function testGetData()
    {
        $options = [
            'method' => $this->testCountMethod
        ];

        $this->assertEquals($this->testCount, $this->instance->getData($options));
    }

    public function testConfigureOptionsCallsParent()
    {
        $resolver = new OptionsResolver();
        $this->instance->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve([]);

        $this->assertEquals($this->parentOptionsValue, $resolvedOptions[$this->parentOptionsKey]);
    }

    public function testGetName()
    {
        $this->assertEquals('repository', $this->instance->getName());
    }

    private function createInstance()
    {
        return new RepositoryDashboardProviderType();
    }

    private function createParentMock()
    {
        $mock = $this->createMock(DashboardProviderType::class);

        $mock->method('getRepository')->willReturnCallback(
            function () {
                return $this->createRepositoryMock();
            }
        );

        $mock->method('configureOptions')->willReturnCallback(
            function (OptionsResolver $resolver) {
                $resolver->setDefaults([
                    $this->parentOptionsKey => $this->parentOptionsValue
                ]);
            }
        );

        return $mock;
    }

    private function createRepositoryMock()
    {
        $mock = $this->createMock(EntityRepository::class);

        $mock->method($this->testCountMethod)->willReturnCallback(
            function () {
                return $this->testCount;
            }
        );

        return $mock;
    }
}
