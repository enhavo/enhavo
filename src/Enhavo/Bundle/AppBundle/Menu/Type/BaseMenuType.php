<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Menu\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Menu\MenuTypeInterface;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseMenuType extends AbstractType implements MenuTypeInterface
{
    public function __construct(
        private readonly ResourceExpressionLanguage $expressionLanguage,
    ) {
    }

    public function createViewData(array $options, Data $data): void
    {
        $data->add([
            'component' => $options['component'],
            'model' => $options['model'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'permission' => null,
            'enabled' => true,
        ]);

        $resolver->setRequired([
            'component',
            'model',
        ]);
    }

    public function isEnabled(array $options, ?object $resource = null): bool
    {
        return (bool) $this->expressionLanguage->evaluate($options['enabled'], [
            'resource' => $resource,
            'action' => $this,
        ]);
    }

    public function getPermission(array $options, ?object $resource = null): mixed
    {
        return $this->expressionLanguage->evaluate($options['permission'], [
            'resource' => $resource,
            'action' => $this,
        ]);
    }

    public static function getName(): ?string
    {
        return 'base';
    }
}
