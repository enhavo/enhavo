<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkActionType extends AbstractActionType
{
    public function __construct(
        private Router $router,
    ) {
    }

    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $url = null;
        if ($resource) {
            $url = $this->router->generate($resource, $options['route_parameters']);
        }

        $data->set('url', $url);
        $data->set('target', $options['target']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'route_parameters' => [],
            'target' => '_blank',
            'model' => 'OpenAction',
            'icon' => 'open_in_browser',
            'label' => 'label.open',
            'translation_domain' => 'EnhavoRoutingBundle',
        ]);
    }

    public static function getName(): ?string
    {
        return 'link';
    }
}
