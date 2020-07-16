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
    public function testGetData()
    {
        $instance = $this->createInstance();
        $parent = $this->createMock(DashboardProviderType::class);

        $queryMock = $this->createMock(AbstractQuery::class);
        $queryMock->method('getSingleScalarResult')->willReturn(765);

        $qbMock = $this->createMock(QueryBuilder::class);
        $qbMock->method('select')->willReturnSelf();
        $qbMock->method('getQuery')->willReturn($queryMock);

        $repositoryMock = $this->createMock(EntityRepository::class);
        $repositoryMock->method('createQueryBuilder')->willReturn($qbMock);

        $parent->method('getRepository')->willReturn($repositoryMock);

        $instance->setParent($parent);

        $this->assertEquals(765, $instance->getData([]));
    }

    public function testConfigureOptionsCallsParent()
    {
        $instance = $this->createInstance();
        $parent = $this->createMock(DashboardProviderType::class);

        $parent->method('configureOptions')->willReturnCallback(
            function (OptionsResolver $resolver) {
                $resolver->setDefaults([
                    'key1' => 'value1'
                ]);
            }
        );

        $instance->setParent($parent);

        $resolver = new OptionsResolver();
        $instance->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve([]);

        $this->assertEquals('value1', $resolvedOptions['key1']);
    }

    public function testGetName()
    {
        $this->assertEquals('total', TotalDashboardProviderType::getName());
    }

    private function createInstance()
    {
        return new TotalDashboardProviderType();
    }
}
