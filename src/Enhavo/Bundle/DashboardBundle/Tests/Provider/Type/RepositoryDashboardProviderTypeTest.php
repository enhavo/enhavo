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
        $parent = $this->createParentMock();
        $parent->method('getRepository')->willReturnCallback(
            function () {
                $repositoryMock = $this->createRepositoryMock();

                $repositoryMock->method('findAll')->willReturnCallback(
                    function () {
                        return 790;
                    }
                );

                return $repositoryMock;
            }
        );

        $instance->setParent($parent);

        $this->assertEquals(790, $instance->getData([
            'method' => 'findAll'
        ]));
    }

    public function testConfigureOptionsCallsParent()
    {
        $instance = $this->createInstance();
        $parent = $this->createParentMock();

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
        $instance = $this->createInstance();

        $this->assertEquals('repository', $instance->getName());
    }

    private function createInstance()
    {
        return new RepositoryDashboardProviderType();
    }

    private function createParentMock()
    {
        return $this->createMock(DashboardProviderType::class);
    }

    private function createRepositoryMock()
    {
        return $this->createMock(EntityRepository::class);
    }
}
