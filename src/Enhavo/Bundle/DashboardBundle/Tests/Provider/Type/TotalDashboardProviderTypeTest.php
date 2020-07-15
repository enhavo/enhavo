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
        $parent = $this->createParentMock();

        $parent->method('getRepository')->willReturnCallback(
            function () {
                $repositoryMock = $this->createRepositoryMock();
                $repositoryMock->method('createQueryBuilder')->willReturnCallback(
                    function () {
                        $qbMock = $this->createQueryBuilderMock();
                        $qbMock->method('select')->willReturnSelf();
                        $qbMock->method('getQuery')->willReturnCallback(
                            function () {
                                $queryMock = $this->createQuery();
                                $queryMock->method('getSingleScalarResult')->willReturnCallback(
                                    function () {
                                        return 765;
                                    }
                                );

                                return $queryMock;
                            }
                        );

                        return $qbMock;
                    }
                );

                return $repositoryMock;
            }
        );
        $instance->setParent($parent);

        $this->assertEquals(765, $instance->getData([]));
    }

    public function testConfigureOptionsCallsParent()
    {
        $instance = $this->createInstance();
        $parent = $this->createParentMock();

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
        $instance = $this->createInstance();

        $this->assertEquals('total', $instance->getName());
    }

    private function createInstance()
    {
        return new TotalDashboardProviderType();
    }

    private function createParentMock()
    {
        return $this->createMock(DashboardProviderType::class);
    }

    private function createRepositoryMock()
    {
        return $this->createMock(EntityRepository::class);
    }

    private function createQueryBuilderMock()
    {
        return $this->createMock(QueryBuilder::class);
    }

    private function createQuery()
    {
        return $this->createMock(AbstractQuery::class);
    }
}
