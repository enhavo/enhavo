<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Input;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Exception\InputException;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Enhavo\Bundle\ResourceBundle\Tab\TabManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

abstract class AbstractInput implements InputInterface, ServiceSubscriberInterface
{
    protected array $options;

    protected ContainerInterface $container;

    public function setOptions($options): void
    {
        $this->options = $options;
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public static function getSubscribedServices(): array
    {
        return [
            ActionManager::class,
            TabManager::class,
            FormFactoryInterface::class,
            ResourceManager::class,
            RequestStack::class,
            ResourceExpressionLanguage::class,
            'form.factory' => '?'.FormFactoryInterface::class,
            SerializerInterface::class,
        ];
    }

    /**
     * @throws InputException
     *
     * @return Action[]
     */
    protected function createActions($configuration, ?object $resource = null): array
    {
        if (!$this->container->has(ActionManager::class)) {
            throw InputException::missingService(ActionManager::class);
        }

        /** @var ActionManager $actionManager */
        $actionManager = $this->container->get(ActionManager::class);

        return $actionManager->getActions($configuration, $resource);
    }

    /**
     * @throws InputException
     *
     * @return Action[]
     */
    protected function createTabs($configuration): array
    {
        if (!$this->container->has(TabManager::class)) {
            throw InputException::missingService(TabManager::class);
        }

        /** @var TabManager $tabManager */
        $tabManager = $this->container->get(TabManager::class);

        return $tabManager->getTabs($configuration, $this);
    }

    protected function getRepository($name): EntityRepository
    {
        if (!$this->container->has(ResourceManager::class)) {
            throw InputException::missingService(ResourceManager::class);
        }

        /** @var ResourceManager $actionManager */
        $manager = $this->container->get(ResourceManager::class);

        return $manager->getRepository($name);
    }

    protected function getFactory($name): FactoryInterface
    {
        if (!$this->container->has(ResourceManager::class)) {
            throw InputException::missingService(ResourceManager::class);
        }

        /** @var ResourceManager $actionManager */
        $manager = $this->container->get(ResourceManager::class);

        return $manager->getFactory($name);
    }

    protected function evaluate($expression, $parameters = [])
    {
        if (!$this->container->has(ResourceExpressionLanguage::class)) {
            throw InputException::missingService(ResourceExpressionLanguage::class);
        }

        /** @var ResourceExpressionLanguage $expressionLanguage */
        $expressionLanguage = $this->container->get(ResourceExpressionLanguage::class);

        return $expressionLanguage->evaluate($expression, $parameters);
    }

    protected function evaluateArray($array, $parameters = []): array
    {
        $newArray = [];
        foreach ($array as $key => $item) {
            $newArray[$key] = $this->evaluate($item, $parameters);
        }

        return $newArray;
    }

    protected function resolveRoute(string $name): ?string
    {
        if (!$this->container->has(RouteResolverInterface::class)) {
            throw InputException::missingService(RouteResolverInterface::class);
        }

        /** @var RouteResolverInterface $routeResolver */
        $routeResolver = $this->container->get(RouteResolverInterface::class);

        return $routeResolver->getRoute($name);
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }

    protected function getRequest(): ?Request
    {
        if (!$this->container->get(RequestStack::class)) {
            throw InputException::missingService(RequestStack::class);
        }

        /** @var RequestStack $requestStack */
        $requestStack = $this->container->get(RequestStack::class);

        return $requestStack->getMainRequest();
    }

    protected function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return $this->container->get(SerializerInterface::class)->normalize($data, $format, $context);
    }
}
