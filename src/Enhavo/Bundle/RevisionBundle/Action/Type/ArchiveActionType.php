<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RevisionBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ArchiveActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly CsrfTokenManagerInterface $tokenManager,
        private readonly ResourceExpressionLanguage $expressionLanguage,
        private readonly RouteResolverInterface $routeResolver,
    ) {
    }

    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $data->set('token', $this->tokenManager->getToken('resource_revision')->getValue());

        $route = $this->expressionLanguage->evaluate($options['route'], [
            'resource' => $resource,
        ]) ?? $this->routeResolver->getRoute('revision_archive', ['api' => true]);

        if (null === $route) {
            throw new \Exception('Can\'t find an api route for archive action, please provide a route over the "route" option');
        }

        $routeParameters = $this->expressionLanguage->evaluateArray($options['route_parameters'], [
            'resource' => $resource,
        ]);

        $data->set('url', $this->router->generate($route, $routeParameters));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'archive.action.archive',
            'translation_domain' => 'EnhavoRevisionBundle',
            'icon' => 'archive',
            'model' => 'ArchiveAction',
            'confirm_label_ok' => 'archive.action.archive',
            'confirm_label_cancel' => 'archive.action.cancel',
            'confirm_message' => 'archive.message.archive',
            'route' => null,
            'route_parameters' => [
                'id' => 'expr:resource.getId()',
            ],
        ]);
    }

    public static function getName(): ?string
    {
        return 'revision_archive';
    }
}
