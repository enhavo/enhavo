<?php

namespace Enhavo\Bundle\DashboardBundle\Dashboard\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\DashboardBundle\Dashboard\DashboardWidgetTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseDashboardWidgetType extends AbstractType implements DashboardWidgetTypeInterface
{
    public function createViewData(array $options, Data $data): void
    {
        $data->set('model', $options['model']);
        $data->set('component', $options['component']);
        $data->set('width', $options['width']);
    }

    public function isEnabled(array $options): bool
    {
        return $options['enabled'];
    }

    public function getPermission(array $options): mixed
    {
        return $options['permission'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'enabled' => true,
            'permission' => null,
            'width' => 4,
            'model' => 'BaseDashboardWidget'
        ]);

        $resolver->setRequired([
            'component'
        ]);
    }

    public static function getName(): ?string
    {
        return 'base';
    }
}
