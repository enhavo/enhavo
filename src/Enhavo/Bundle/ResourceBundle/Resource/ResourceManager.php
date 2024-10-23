<?php

namespace Enhavo\Bundle\ResourceBundle\Resource;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Event\ResourcePreCreateEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourcePostCreateEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourcePostDeleteEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourcePostTransitionEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourcePostUpdateEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourcePreDeleteEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourcePreTransitionEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourcePreUpdateEvent;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SM\Factory\FactoryInterface as SMFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResourceManager
{
    private ContainerInterface $container;
    private PropertyAccessor $propertyAccessor;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EntityManagerInterface $em,
        private readonly SMFactoryInterface $stateMachineFactory,
        private readonly ValidatorInterface $validator,
    ) {
        $this->propertyAccessor = new PropertyAccessor();
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function save(object $resource): void
    {
        $id = $this->propertyAccessor->getValue($resource, 'id');

        if ($id === null) {
            $this->dispatch(new ResourcePreCreateEvent($resource), 'pre_create');
            $this->em->persist($resource);
            $this->em->flush();
            $this->dispatch(new ResourcePostCreateEvent($resource), 'post_create');
        } else {
            $this->dispatch(new ResourcePreUpdateEvent($resource), 'pre_update');
            $this->em->flush();
            $this->dispatch(new ResourcePostUpdateEvent($resource), 'post_update');
        }
    }

    public function applyTransition(object $resource, string $transition = null, string $graph = null): void
    {
        $this->dispatch(new ResourcePreTransitionEvent($resource, $transition, $graph), 'pre_transition');

        if ($transition && $graph) {
            $this->stateMachineFactory->get($resource, $graph)->apply($transition);
        }

        $this->dispatch(new ResourcePostTransitionEvent($resource, $transition, $graph), 'post_transition');
    }

    public function canApplyTransition(object $resource, ?string $transition = null, ?string $graph = null): bool
    {
        return $this->stateMachineFactory->get($resource, $graph)->can($transition);
    }

    public function validate(object $resource, Constraint|array|null $constraints = null, string|GroupSequence|array|null $groups = null): ConstraintViolationListInterface
    {
        return $this->validator->validate($resource, $constraints, $groups);
    }

    public function isValid(object $resource, Constraint|array|null $constraints = null, string|GroupSequence|array|null $groups = null): bool
    {
        return $this->validator->validate($resource, $constraints, $groups)->count() === 0;
    }

    public function delete(object $resource): void
    {
        $this->dispatch(new ResourcePreDeleteEvent($resource), 'pre_delete');

        $this->em->remove($resource);
        $this->em->flush();

        $this->dispatch(new ResourcePostDeleteEvent($resource), 'post_delete');
    }

    public function duplicate(object $resource): object
    {
        return clone $resource;
    }

    public function getRepository($name): EntityRepository
    {
        return $this->container->get(sprintf('%s.repository', $name));
    }

    public function getFactory($name): FactoryInterface
    {
        return $this->container->get(sprintf('%s.factory', $name));
    }

    private function dispatch($event, $eventName): void
    {
        $this->eventDispatcher->dispatch($event, sprintf('enhavo_resource.%s', $eventName));
    }
}
