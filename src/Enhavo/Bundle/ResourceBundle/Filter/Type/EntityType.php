<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Filter\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class EntityType extends AbstractFilterType
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
    )
    {
    }

    public function createViewData($options, Data $data): void
    {
        $choices = $this->getChoices($options);

        $data->add([
            'choices' => $choices,
        ]);
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

    protected function getInitialValue($options)
    {
        if ($options['initial_value'] === null) {
            return 0;
        }

        $repository = $this->resourceManager->getRepository($options['resource']);

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

        $propertyAccessor = new PropertyAccessor();
        return $propertyAccessor->getValue($initialValueEntity, 'id');
    }

    private function getChoices($options): array
    {
        $repository = $this->resourceManager->getRepository($options['resource']);

        $method = $options['method'];
        $arguments =  $options['arguments'];

        if(is_array($arguments)) {
            $entities = call_user_func_array([$repository, $method], $arguments);
        } else {
            $entities = call_user_func([$repository, $method]);
        }

        $propertyAccessor = new PropertyAccessor();
        $choiceLabel = $options['choice_label'];
        $choices = [];
        foreach ($entities as $entity) {
            if ($choiceLabel) {
                $label = $propertyAccessor->getValue($entity, $choiceLabel);
            } else {
                $label = (string)$entity;
            }
            $choices[] = [
                'label' => $label,
                'code' => $propertyAccessor->getValue($entity, 'id')
            ];
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'findAll',
            'arguments' => null,
            'choice_label' => null,
            'component' => 'filter-entity',
            'model' => 'EntityFilter',
            'initial_value_arguments' => null
        ]);

        $resolver->setRequired([
            'resource'
        ]);
    }

    public static function getName(): ?string
    {
        return 'entity';
    }
}
