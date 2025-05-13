<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Form\Transformer;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class EntityTransformer implements DataTransformerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var string */
    private $class;

    /** @var string */
    private $property;

    /** @var \Closure|null */
    private $queryBuilder;

    /** @var \Closure|null */
    private $factory;

    /**
     * EntityTransformer constructor.
     */
    public function __construct(EntityManagerInterface $em, string $class, string $property, ?\Closure $queryBuilder, \Closure $factory)
    {
        $this->em = $em;
        $this->class = $class;
        $this->property = $property;
        $this->queryBuilder = $queryBuilder;
        $this->factory = $factory;
    }

    public function transform($entity)
    {
        if (null === $entity) {
            return '';
        }

        $propertyAccessor = new PropertyAccessor();

        return $propertyAccessor->getValue($entity, $this->property);
    }

    public function reverseTransform($string)
    {
        if ('' === trim($string)) {
            return null;
        }

        $repository = $this->em->getRepository($this->class);
        if ($this->queryBuilder instanceof \Closure) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $this->queryBuilder->call($this, $repository);
            if (null === $queryBuilder) {
                throw new \InvalidArgumentException(sprintf('Option query_builder need to return a QueryBuilder. Maybe you forgot a return.'));
            }
            $alias = $queryBuilder->getRootAliases();
            if (!isset($alias[0])) {
                throw new \InvalidArgumentException(sprintf('Option query_builder need to return a QueryBuilder with at least one root alias'));
            }

            $queryBuilder->andWhere(sprintf('%s.%s = :propertyValue', $alias[0], $this->property));
            $queryBuilder->setParameter('propertyValue', $string);

            $entity = $queryBuilder->getQuery()->getResult();
            if (is_array($entity)) {
                $entity = count($entity) ? $entity[0] : null;
            }
        } else {
            $entity = $repository->findOneBy([
                $this->property => $string,
            ]);
        }

        if (null === $entity) {
            if ($this->factory instanceof \Closure) {
                $entity = $this->factory->call($this);
            } else {
                $entity = new $this->class();
            }

            $propertyAccessor = new PropertyAccessor();
            $propertyAccessor->setValue($entity, $this->property, $string);
        }

        return $entity;
    }
}
