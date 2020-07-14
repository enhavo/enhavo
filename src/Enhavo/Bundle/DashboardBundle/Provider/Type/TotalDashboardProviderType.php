<?php


namespace Enhavo\Bundle\DashboardBundle\Provider\Type;


use Enhavo\Bundle\DashboardBundle\Provider\AbstractDashboardProviderType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TotalDashboardProviderType extends AbstractDashboardProviderType
{
    /**
     * @var DashboardProviderType
     */
    protected $parent;

    public function getData($options)
    {
        $repository = $this->parent->getRepository($options);

        return $repository->createQueryBuilder('s')
            ->select('count(s.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $this->parent->configureOptions($resolver);
    }

    public static function getName(): ?string
    {
        return 'total';
    }
}
