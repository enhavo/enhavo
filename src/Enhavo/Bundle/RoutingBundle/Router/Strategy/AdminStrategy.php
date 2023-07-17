<?php

namespace Enhavo\Bundle\RoutingBundle\Router\Strategy;

use Enhavo\Bundle\AppBundle\Util\StateEncoder;
use Enhavo\Bundle\RoutingBundle\Router\AbstractStrategy;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdminStrategy extends AbstractStrategy
{
    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = [])
    {
        $id = $this->getProperty($resource, $options['id_property']);
        $parameters = array_merge($parameters, ['id' => $id]);
        $updateUrl = $this->getRouter()->generate($options['update_route'], $parameters);
        $indexUrl = $this->getRouter()->generate($options['index_route']);

        $state = [
            'views' => [
                [
                    'url' => $indexUrl,
                    'id' => '1',
                    'storage' => [
                        ['key' => 'edit-view', 'value' => 2]
                    ]
                ],
                [
                    'url' => $updateUrl,
                    'id' => '2'
                ]
            ],
            'storage' => []
        ];

        return $this->getRouter()->generate('enhavo_app_index', [ 'state' => StateEncoder::encode($state) ], $referenceType);
    }

    public function getType()
    {
        return 'admin';
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'id_property' => 'id',
        ]);
        $optionsResolver->setRequired([
            'index_route',
            'update_route',
        ]);
    }
}
