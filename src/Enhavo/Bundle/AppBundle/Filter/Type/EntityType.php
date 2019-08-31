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
        $choices = $this->getChoices($options);

        $data = [
            'type' => $this->getType(),
            'choices' => $choices,
            'key' => $name,
            'value' => null,
            'initialValue' => null,
            'component' => $options['component'],
            'label' => $this->getLabel($options),
        ];

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

    private function getChoices($options)
    {
        $repository = $this->getRepository($options);

        $method = $options['method'];
        $arguments =  $options['arguments'];

        if(is_array($arguments)) {
            $entities = call_user_func([$repository, $method], $arguments);
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
            'path' => null,
            'choice_label' => null,
            'component' => 'filter-entity'
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
