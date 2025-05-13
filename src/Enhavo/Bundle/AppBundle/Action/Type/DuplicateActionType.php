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
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DuplicateActionType extends AbstractActionType implements ActionTypeInterface
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly RouteResolverInterface $routeResolver,
        private readonly ResourceExpressionLanguage $expressionLanguage,
        private readonly CsrfTokenManagerInterface $tokenManager,
    ) {
    }

    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $data->set('token', $this->tokenManager->getToken('resource_duplicate')->getValue());

        $route = $this->expressionLanguage->evaluate($options['route'], [
            'resource' => $resource,
        ]);

        if (null === $route) {
            $route = $this->routeResolver->getRoute('duplicate', ['api' => true]);
        }

        if (null === $route) {
            throw new \Exception('Can\'t find create route, please provide a route over the "route" option');
        }

        $routeParameters = $this->expressionLanguage->evaluateArray($options['route_parameters'], [
            'resource' => $resource,
        ]);

        $data->set('url', $this->router->generate($route, $routeParameters));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'label.duplicate',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'content_copy',
            'confirm' => true,
            'confirm_message' => 'message.duplicate.confirm',
            'confirm_label_ok' => 'action.duplicate',
            'confirm_label_cancel' => 'label.cancel',
            'route' => null,
            'route_parameters' => ['id' => 'expr:resource.getId()'],
            'model' => 'DuplicateAction',
        ]);
    }

    public static function getName(): ?string
    {
        return 'duplicate';
    }
}
