<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.11.17
 */

namespace Enhavo\Bundle\AppBundle\Column\Type;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGenerator;

class UrlType extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        $resolverType = $this->getOption('resolver_type', $options, 'default');
        /** @var Router $router */
        $router = $this->container->get('enhavo_routing.router');
        $url = $router->generate($resource, [], UrlGenerator::ABSOLUTE_PATH, $resolverType);
        return $url;
    }

    public function createColumnViewData(array $options)
    {
        $data = parent::createColumnViewData($options);

        $data = array_merge($data, [
            'action' => [
                'component' => 'open-action',
                'target' => $options['target'],
                'icon' => $options['icon'],
            ],
            'mapping' => 'url'
        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'component' => 'column-action',
            'icon' => 'link',
            'target' => '_blank',
            'resolver_type' => 'default'
        ]);
    }

    public function getType()
    {
        return 'url';
    }
}
