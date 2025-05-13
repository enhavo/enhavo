<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly Router $router,
    ) {
    }

    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        $url = $this->router->generate($resource, [], UrlGeneratorInterface::ABSOLUTE_PATH, $options['resolver_type']);
        $data->set('url', $url);
        $data->set('target', $options['target']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-action',
            'model' => 'ActionColumn',
            'icon' => 'link',
            'target' => '_blank',
            'resolver_type' => 'default',
        ]);
    }

    public static function getName(): ?string
    {
        return 'url';
    }
}
