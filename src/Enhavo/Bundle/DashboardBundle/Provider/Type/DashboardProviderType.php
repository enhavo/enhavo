<?php


namespace Enhavo\Bundle\DashboardBundle\Provider\Type;


use Enhavo\Component\Type\AbstractType;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardProviderType extends AbstractType
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DashboardProviderType constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $options
     * @return EntityRepository
     */
    public function getRepository($options)
    {
        $repository = null;
        if($this->container->has($options['repository'])) {
            $repository = $this->container->get($options['repository']);
        } else {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $repository = $em->getRepository($options['repository']);
        }

        if(!$repository instanceof EntityRepository) {
            throw new \InvalidArgumentException(sprintf(
                'Repository should to be type of "%s", but got "%s"',
                EntityRepository::class,
                get_class($repository)
            ));
        }
        return $repository;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'method' => null,
        ]);

        $optionsResolver->setRequired([
            'repository'
        ]);
    }
}
