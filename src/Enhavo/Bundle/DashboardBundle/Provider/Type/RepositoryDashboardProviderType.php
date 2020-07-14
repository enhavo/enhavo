<?php


namespace Enhavo\Bundle\DashboardBundle\Provider\Type;


use Enhavo\Bundle\DashboardBundle\Provider\ProviderTypeInterface;
use Enhavo\Bundle\DashboardBundle\Provider\AbstractDashboardProviderType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepositoryDashboardProviderType extends AbstractDashboardProviderType implements ProviderTypeInterface
{
    /**
     * @var DashboardProviderType
     */
    protected $parent;

    public function getData($options)
    {
        $repository = $this->parent->getRepository($options);

        return call_user_func([$repository, $options['method']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $this->parent->configureOptions($resolver);

        $resolver->setDefaults([
            'method' => 'findAll',
        ]);
    }

    public static function getName(): ?string
    {
        return 'repository';
    }
}
