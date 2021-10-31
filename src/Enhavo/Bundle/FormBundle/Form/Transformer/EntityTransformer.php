<?php

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
     * @param EntityManagerInterface $em
     * @param string $class
     * @param string $property
     * @param \Closure|null $queryBuilder
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
        if ($entity === null) {
            return '';
        }

        $propertyAccessor = new PropertyAccessor();
        return $propertyAccessor->getValue($entity, $this->property);
    }

    public function reverseTransform($string)
    {
        if (trim($string) === '') {
            return null;
        }

        $repository = $this->em->getRepository($this->class);
        if ($this->queryBuilder instanceof \Closure) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $this->queryBuilder->call($this, $repository);
            if ($queryBuilder === null) {
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
                $this->property => $string
            ]);
        }

        if ($entity === null) {
            if ($this->factory instanceof \Closure) {
                $entity = $this->factory->call($this);
            } else {
                $entity = new $this->class;
            }

            $propertyAccessor = new PropertyAccessor();
            $propertyAccessor->setValue($entity, $this->property, $string);
        }

        return $entity;
    }
}
