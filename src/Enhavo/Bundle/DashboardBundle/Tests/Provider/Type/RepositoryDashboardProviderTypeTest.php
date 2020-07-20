<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Provider\Type;


use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\DashboardBundle\Provider\Type\DashboardProviderType;
use Enhavo\Bundle\DashboardBundle\Provider\Type\RepositoryDashboardProviderType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepositoryDashboardProviderTypeTest extends TestCase
{
    public function testGetData()
    {
        $instance = $this->createInstance();
        $parent = $this->createMock(DashboardProviderType::class);

        $repositoryMock = $this->createMock(EntityRepository::class);
        $repositoryMock->method('findAll')->willReturn(790);

        $parent->method('getRepository')->willReturn($repositoryMock);

        $instance->setParent($parent);

        $this->assertEquals(790, $instance->getData([
            'method' => 'findAll'
        ]));
    }

    public function testConfigureOptionsCallsParent()
    {
        $instance = $this->createInstance();
        $parent = $this->createMock(DashboardProviderType::class);

        $parent->method('configureOptions')->willReturnCallback(
            function (OptionsResolver $resolver) {
                $resolver->setDefaults([
                    'parentKey' => 'parentValue'
                ]);
            }
        );

        $instance->setParent($parent);

        $resolver = new OptionsResolver();
        $instance->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve([]);

        $this->assertEquals('parentValue', $resolvedOptions['parentKey']);
    }

    public function testGetName()
    {
        $this->assertEquals('repository', RepositoryDashboardProviderType::getName());
    }

    private function createInstance()
    {
        return new RepositoryDashboardProviderType();
    }
}
