<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UrlActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    ) {
    }

    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $data->set('url', $options['url'] ?? $this->getUrl($options, $resource));
    }

    private function getUrl(array $options, ?object $resource = null): string
    {
        $parameters = $this->expressionLanguage->evaluateArray($options['route_parameters'], [
            'resource' => $resource,
        ]);

        $route = $this->expressionLanguage->evaluate($options['route'], [
            'resource' => $resource,
        ]);

        return $this->router->generate($route, $parameters);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'route_parameters' => [],
            'route' => null,
            'url' => null,
        ]);

        $resolver->setNormalizer('route', function ($options, $value) {
            if (null === $options['url'] && null === $value) {
                throw new InvalidOptionsException('Need to configure "route" or "url" option');
            }

            return $value;
        });
    }
}
