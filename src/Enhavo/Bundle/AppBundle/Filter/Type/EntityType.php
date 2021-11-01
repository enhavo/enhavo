<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Type;

use Enhavo\Bundle\AppBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityType extends AbstractFilterType
{
    public function createViewData($options, $name)
    {
        $data = parent::createViewData($options, $name);
        $choices = $this->getChoices($options);

        $data = array_merge($data, [
            'choices' => $choices,
        ]);

        return $data;
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if ($value == '') {
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

        $repository = $this->getRepository($options);

        $method = $options['initial_value'];
        $arguments =  $options['initial_value_arguments'];

        $reflectionClass = new \ReflectionClass(get_class($repository));
        if (!$reflectionClass->hasMethod($options['initial_value'])) {
            throw new \InvalidArgumentException('Parameter "initial_value" must be a method of the repository defined by parameter "repository"');
        }

        if($arguments) {
            if (!is_array($arguments)) {
                $arguments = [$arguments];
            }
            $initialValueEntity = call_user_func([$repository, $method], $arguments);
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
        $repository = $this->getRepository($options);

        $method = $options['method'];
        $arguments =  $options['arguments'];

        if(is_array($arguments)) {
            $entities = call_user_func_array([$repository, $method], $arguments);
        } else {
            $entities = call_user_func([$repository, $method]);
        }

        $choiceLabel = $this->getOption('choice_label', $options);
        $choices = [];
        foreach ($entities as $entity) {
            if ($choiceLabel) {
                $label = $this->getProperty($entity, $choiceLabel);
            } else {
                $label = (string)$entity;
            }
            $choices[] = [
                'label' => $label,
                'code' => $this->getProperty($entity, 'id')
            ];
        }
        return $choices;
    }

    /**
     * @param array $options
     * @return EntityRepository
     */
    private function getRepository($options)
    {
        $repository = null;
        if($this->container->has($options['repository'])) {
            $repository =  $this->container->get($options['repository']);
        } else {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $repository = $em->getRepository($options['repository']);
        }

        if(!$repository instanceof EntityRepository) {
            throw new \InvalidArgumentException(sprintf(
                'Repository should to be type of "%s", but got "%s"',
                EntityRepository::class,
                get_class($repository)
            ));
        }
        return $repository;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'method' => 'findAll',
            'arguments' => null,
            'choice_label' => null,
            'component' => 'filter-entity',
            'initial_value_arguments' => null
        ]);

        $optionsResolver->setRequired([
            'repository'
        ]);
    }

    public function getType()
    {
        return 'entity';
    }
}
