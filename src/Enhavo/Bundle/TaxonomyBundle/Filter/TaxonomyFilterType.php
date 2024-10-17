<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-25
 * Time: 19:38
 */

namespace Enhavo\Bundle\TaxonomyBundle\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class TaxonomyFilterType extends AbstractFilterType
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function createViewData($options, Data $data): void
    {
        $choices = $this->getChoices($options);
        $data['choices'] = $choices;
    }

    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        if ($value == null) {
            return;
        }

        $property = $options['property'];
        $propertyPath = explode('.', $property);
        $query->addWhere('id', FilterQuery::OPERATOR_EQUALS, $value, $propertyPath);
    }

    private function getInitialValue($options)
    {
        if ($options['initial_value'] === null) {
            return 0;
        }

        $repository = $this->em->getRepository(Term::class);
        $method = $options['initial_value_method'];
        if ($options['initial_value_arguments']) {
            $arguments = $options['initial_value_arguments'];
        } else {
            $arguments = [$options['initial_value'], $options['taxonomy']];
        }

        $reflectionClass = new \ReflectionClass(get_class($repository));
        if (!$reflectionClass->hasMethod($options['initial_value_method'])) {
            throw new \InvalidArgumentException('Parameter "initial_value_method" must be a method of the repository defined by parameter "repository"');
        }

        if ($arguments) {
            if (!is_array($arguments)) {
                $arguments = [$arguments];
            }
            $initialValueEntity = call_user_func_array([$repository, $method], $arguments);
        } else {
            $initialValueEntity = call_user_func([$repository, $method]);
        }

        if (!$initialValueEntity || (is_array($initialValueEntity) && count($initialValueEntity) == 0)) {
            return null;
        }
        if (is_array($initialValueEntity) && count($initialValueEntity) > 0) {
            $initialValueEntity = $initialValueEntity[0];
        }

        $propertyAccessor = new PropertyAccessor();
        return $propertyAccessor->getValue($initialValueEntity, 'id');
    }

    private function getChoices($options): array
    {
        $propertyAccessor = new PropertyAccessor();
        $repository = $this->em->getRepository(Term::class);
        $entities = $repository->findByTaxonomyName($options['taxonomy']);
        $choices = [];
        foreach ($entities as $entity) {
            $choices[] = [
                'label' => $propertyAccessor->getValue($entity, 'name'),
                'code' => $propertyAccessor->getValue($entity, 'id')
            ];
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'filter-dropdown',
            'initial_value_method' => 'findOneByNameAndTaxonomy',
            'initial_value_arguments' => null,
            'model' => 'OptionFilter',
        ]);

        $resolver->setRequired([
            'taxonomy',
            'property',
        ]);
    }

    public static function getName(): ?string
    {
        return 'taxonomy';
    }
}
