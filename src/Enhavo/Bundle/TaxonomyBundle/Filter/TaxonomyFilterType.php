<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-25
 * Time: 19:38
 */

namespace Enhavo\Bundle\TaxonomyBundle\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TaxonomyFilterType extends AbstractFilterType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(TranslatorInterface $translator, EntityManagerInterface $em)
    {
        parent::__construct($translator);
        $this->em = $em;
    }

    public function createViewData($options, $name)
    {
        $choices = $this->getChoices($options);
        $data = parent::createViewData($options, $name);
        $data['choices'] = $choices;
        return $data;
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if ($value == null) {
            return;
        }

        $property = $options['property'];
        $propertyPath = explode('.', $property);
        $query->addWhere('id', FilterQuery::OPERATOR_EQUALS, $value, $propertyPath);
    }

    protected function getInitialValue($options)
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

        if($arguments) {
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

        return $this->getProperty($initialValueEntity, 'id');
    }

    private function getChoices($options)
    {
        $repository = $this->em->getRepository(Term::class);
        $entities = $repository->findByTaxonomyName($options['taxonomy']);
        $choices = [];
        foreach ($entities as $entity) {
            $choices[] = [
                'label' => $this->getProperty($entity, 'name'),
                'code' => $this->getProperty($entity, 'id')
            ];
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'component' => 'filter-entity',
            'initial_value_method' => 'findOneByNameAndTaxonomy',
            'initial_value_arguments' => null
        ]);

        $optionsResolver->setRequired([
            'taxonomy'
        ]);
    }

    public function getType()
    {
        return 'taxonomy';
    }
}
