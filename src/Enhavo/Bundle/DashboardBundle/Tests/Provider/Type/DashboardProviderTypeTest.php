<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Provider\Type;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DashboardBundle\Provider\Type\DashboardProviderType;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardProviderTypeTest extends TestCase
{
    public function testGetRepositoryViaContainer()
    {
        $dependencies = $this->createDependencies();

        $dependencies->container->method('has')->willReturnCallback(
            function ($name) {
                return $name === 'repository.test';
            }
        );

        $dependencies->container->method('get')->willReturn($this->createMock(EntityRepository::class));

        $instance = $this->createInstance($dependencies);

        $this->assertInstanceOf(EntityRepository::class, $instance->getRepository([
            'repository' => 'repository.test'
        ]));
    }

    public function testGetRepositoryViaEntityManager()
    {
        $dependencies = $this->createDependencies();

        $dependencies->container->method('has')->willReturn(false);

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->method('getRepository')->willReturn($this->createMock(EntityRepository::class));

        $dependencies->container->method('get')->willReturn($emMock);

        $instance = $this->createInstance($dependencies);

        $this->assertInstanceOf(EntityRepository::class, $instance->getRepository([
            'repository' => 'repository.test'
        ]));
    }

    public function testGetRepositoryIsNotEntityRepository()
    {
        $dependencies = $this->createDependencies();

        $dependencies->container->method('has')->willReturn(true);

        $dependencies->container->method('get')->willReturnCallback(
            function () {
                return $this;
            }
        );

        $instance = $this->createInstance($dependencies);

        $this->expectException(\InvalidArgumentException::class);
        $instance->getRepository([
            'repository' => 'repository.test'
        ]);
    }

    public function testConfigureOptionsRequiredParametersSet()
    {
        $dependencies = $this->createDependencies();

        $instance = $this->createInstance($dependencies);

        $optionsResolver = new OptionsResolver();
        $instance->configureOptions($optionsResolver);

        $this->expectException(MissingOptionsException::class);
        $optionsResolver->resolve([]);
    }

    private function createInstance(DashboardProviderTypeTestDependencies $dependencies)
    {
        return new DashboardProviderType($dependencies->container);
    }

    private function createDependencies()
    {
        $dependencies = new DashboardProviderTypeTestDependencies();
        $dependencies->container = $this->createMock(ContainerInterface::class);
        $dependencies->em = $this->createMock(EntityManagerInterface::class);
        return $dependencies;
    }
}

class DashboardProviderTypeTestDependencies
{
    public $container;

    public $em;
}
