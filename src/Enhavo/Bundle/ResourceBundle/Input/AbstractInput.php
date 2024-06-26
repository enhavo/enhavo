<?php

namespace Enhavo\Bundle\ResourceBundle\Input;

use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Exception\GridException;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Psr\Container\ContainerInterface;
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
        ];
    }

    /**
     * @return Action[]
     * @throws GridException
     */
    protected function getActions($configuration, ResourceInterface $resource = null): array
    {
        if (!$this->container->has(ActionManager::class)) {
            throw GridException::missingService(ActionManager::class);
        }

        /** @var ActionManager $actionManager */
        $actionManager = $this->container->get(ActionManager::class);

        return $actionManager->getActions($configuration, $resource);
    }
}
