<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Toolbar\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Toolbar\ToolbarWidgetTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseToolbarWidgetType extends AbstractType implements ToolbarWidgetTypeInterface
{
    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $data->set('component', $options['component']);
        $data->set('model', $options['model']);
    }

    public function getPermission(array $options, ?object $resource = null): mixed
    {
        return $options['permission'];
    }

    public function isEnabled(array $options, ?object $resource = null): bool
    {
        return $options['enabled'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'enabled' => true,
            'model' => 'BaseToolbarWidget',
            'permission' => null,
        ]);

        $resolver->setRequired([
            'component',
        ]);
    }
}
