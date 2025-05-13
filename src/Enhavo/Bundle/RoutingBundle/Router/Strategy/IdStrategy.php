<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Router\Strategy;

use Enhavo\Bundle\RoutingBundle\Router\AbstractStrategy;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IdStrategy extends AbstractStrategy
{
    public function generate($resource, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = [])
    {
        $id = $this->getProperty($resource, $options['property']);
        $parameters = array_merge($parameters, ['id' => $id]);

        return $this->getRouter()->generate($options['route'], $parameters, $referenceType);
    }

    public function getType()
    {
        return 'id';
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'property' => 'id',
        ]);
        $optionsResolver->setRequired([
            'route',
        ]);
    }
}
