<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Batch\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseBatchType extends AbstractBatchType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly RouteResolverInterface $routeResolver,
        private readonly RouterInterface $router,
        private readonly CsrfTokenManagerInterface $tokenManager,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    ) {
    }

    public function createViewData(array $options, Data $data): void
    {
        $route = $options['route'];
        if (null === $route) {
            $route = $this->routeResolver->getRoute('batch', ['api' => true]);
        }

        if (null === $route) {
            throw new \Exception('Can\'t find batch route, please provide a route over the "route" option');
        }

        $data->set('label', $this->getLabel($options));
        $data->set('confirmMessage', $this->getConfirmMessage($options));
        $data->set('position', $options['position']);
        $data->set('model', $options['model']);
        $data->set('url', $this->router->generate($route, $options['route_parameters']));
        $data->set('token', $this->tokenManager->getToken('resource_batch')->getValue());
    }

    private function getLabel($options): string
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }

    private function getConfirmMessage($options): ?string
    {
        if (null !== $options['confirm_message']) {
            return $this->translator->trans($options['confirm_message'], [], $options['translation_domain']);
        }

        return null;
    }

    public function getPermission(array $options, EntityRepository $repository): mixed
    {
        return $this->expressionLanguage->evaluate($options['permission'], [
            'repository' => $repository,
            'batch' => $this,
        ]);
    }

    public function isEnabled(array $options): bool
    {
        return (bool) $this->expressionLanguage->evaluate($options['enabled'], [
            'batch' => $this,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'permission' => null,
            'position' => 0,
            'translation_domain' => null,
            'enabled' => true,
            'confirm_message' => null,
            'model' => 'UrlBatch',
            'route' => null,
            'route_parameters' => [],
        ]);

        $resolver->setRequired(['label']);
    }

    public static function getParentType(): ?string
    {
        return null;
    }
}
