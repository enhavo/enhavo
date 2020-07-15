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
    private $repositoryNameViaContainer = 'repository.test';
    private $repositoryNameViaEntityManager = 'TestRepository';
    private $repositoryNameViaContainerInvalid = 'repository.invalid';
    private $emId = 'doctrine.orm.entity_manager';

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setUp()
    {
        $this->container = $this->createContainerMock();
        $this->container->method('has')->willReturnCallback(
            function ($name) {
                return in_array($name, [
                    $this->repositoryNameViaContainer,
                    $this->repositoryNameViaContainerInvalid
                ]);
            }
        );

        $this->container->method('get')->willReturnCallback(
            function ($name) {
                if($name === $this->repositoryNameViaContainer){
                    return $this->createEntityRepositoryMock();
                } else if($name === $this->emId){
                    return $this->createEntityManagerMock();
                } else if($name === $this->repositoryNameViaContainerInvalid) {
                    return $this;
                }
                return null;
            }
        );
    }

    public function testGetRepositoryViaContainer()
    {
        $options = [
            'repository' => $this->repositoryNameViaContainer
        ];

        $dashboardProviderType = new DashboardProviderType($this->container);

        $entityRepositoryMock = $this->container->get($this->repositoryNameViaContainer);

        $this->assertEquals($entityRepositoryMock, $dashboardProviderType->getRepository($options));
    }

    public function testGetRepositoryViaEntityManager()
    {
        $options = [
            'repository' => $this->repositoryNameViaEntityManager
        ];

        $dashboardProviderType = new DashboardProviderType($this->container);

        $entityRepositoryMock = $this->container->get($this->emId)->getRepository('');

        $this->assertEquals($entityRepositoryMock, $dashboardProviderType->getRepository($options));
    }

    public function testGetRepositoryIsNotEntityRepository()
    {
        $options = [
            'repository' => $this->repositoryNameViaContainerInvalid
        ];

        $dashboardProviderType = new DashboardProviderType($this->container);

        $this->expectException(\InvalidArgumentException::class);
        $dashboardProviderType->getRepository($options);
    }

    public function testConfigureOptionsRequiredParametersSet()
    {
        $optionsResolver = new OptionsResolver();
        $dashboardProviderType = new DashboardProviderType($this->container);
        $dashboardProviderType->configureOptions($optionsResolver);

        $this->expectException(MissingOptionsException::class);
        $optionsResolver->resolve([]);
    }

    private function createContainerMock()
    {
        return $this->createMock(ContainerInterface::class);
    }

    private function createEntityRepositoryMock()
    {
        return $this->createMock(EntityRepository::class);
    }

    private function createEntityManagerMock()
    {
        $mock = $this->createMock(EntityManagerInterface::class);
        $mock->method('getRepository')->willReturn($this->createEntityRepositoryMock());

        return $mock;
    }
}
