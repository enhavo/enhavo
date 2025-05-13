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
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author gseidel
 */
class CreateActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly RouteResolverInterface $routeResolver,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    ) {
    }

    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $route = $this->expressionLanguage->evaluate($options['route']) ?? $this->routeResolver->getRoute('create', ['api' => false]);

        if (null === $route) {
            throw new \Exception('Can\'t find create route, please provide a route over the "route" option');
        }

        $routeParameters = $this->expressionLanguage->evaluateArray($options['route_parameters']);

        $data->set('url', $this->router->generate($route, $routeParameters));
        $data->set('frameKey', $options['frame_key']);
        $data->set('target', $options['target']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'add_circle_outline',
            'label' => 'label.create',
            'translation_domain' => 'EnhavoAppBundle',
            'frame_key' => 'edit',
            'target' => '_frame',
            'route' => null,
            'route_parameters' => [],
            'model' => 'OpenAction',
        ]);
    }

    public static function getName(): ?string
    {
        return 'create';
    }
}
