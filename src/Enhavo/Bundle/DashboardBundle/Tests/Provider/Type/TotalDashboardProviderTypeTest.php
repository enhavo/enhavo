<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Provider\Type;


use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Enhavo\Bundle\DashboardBundle\Provider\Type\DashboardProviderType;
use Enhavo\Bundle\DashboardBundle\Provider\Type\TotalDashboardProviderType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TotalDashboardProviderTypeTest extends TestCase
{
    private $instance;

    private $testCount = 789;
    private $parentOptionsKey = 'test_key';
    private $parentOptionsValue = 'test_value';

    public function setUp()
    {
        $this->instance = $this->createInstance();
        $this->instance->setParent($this->createParentMock());
    }

    public function testGetData()
    {
        $this->assertEquals($this->testCount, $this->instance->getData([]));
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
        $this->assertEquals('total', $this->instance->getName());
    }

    private function createInstance()
    {
        return new TotalDashboardProviderType();
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

        $mock->method('createQueryBuilder')->willReturnCallback(
            function () {
                return $this->createQueryBuilderMock();
            }
        );

        return $mock;
    }

    private function createQueryBuilderMock()
    {
        $mock = $this->createMock(QueryBuilder::class);

        $mock->method('select')->willReturnSelf();

        $mock->method('getQuery')->willReturnCallback(
            function () {
                return $this->createQuery();
            }
        );

        return $mock;
    }

    private function createQuery()
    {
        $query = $this->createMock(AbstractQuery::class);

        $query->method('getSingleScalarResult')->willReturnCallback(
            function () {
                return $this->testCount;
            }
        );

        return $query;
    }
}
