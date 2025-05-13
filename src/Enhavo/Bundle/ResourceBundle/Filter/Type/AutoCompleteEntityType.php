<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Filter\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\RouterInterface;

class AutoCompleteEntityType extends AbstractFilterType
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly ResourceManager $resourceManager,
    ) {
    }

    public function createViewData($options, Data $data): void
    {
        $initialValue = $this->getInitialValue($options);

        $data->add([
            'choices' => [],
            'initialValue' => $initialValue,
            'value' => $initialValue ? $initialValue['code'] : null,
            'url' => $this->router->generate($options['route'], $options['route_parameters']),
            'multiple' => false,
            'minimumInputLength' => $options['minimum_input_length'],
        ]);
    }

    private function getInitialValue($options): ?array
    {
        if (null === $options['initial_value']) {
            return null;
        }

        if (!$options['initial_value_repository']) {
            throw new \InvalidArgumentException('If parameter "initial_value" is used, the parameter "initial_value_repository" needs to be set as well');
        }

        $repository = $this->resourceManager->getRepository($options['resource']);

        $method = $options['initial_value'];
        $arguments = $options['initial_value_arguments'];

        $reflectionClass = new \ReflectionClass(get_class($repository));
        if (!$reflectionClass->hasMethod($options['initial_value'])) {
            throw new \InvalidArgumentException('Parameter "initial_value" must be a method of the repository defined by parameter "repository"');
        }

        if ($arguments) {
            if (!is_array($arguments)) {
                $arguments = [$arguments];
            }
            $initialValueEntity = call_user_func_array([$repository, $method], $arguments);
        } else {
            $initialValueEntity = call_user_func([$repository, $method]);
        }

        if (!$initialValueEntity || (is_array($initialValueEntity) && 0 == count($initialValueEntity))) {
            return null;
        }
        if (is_array($initialValueEntity) && count($initialValueEntity) > 0) {
            $initialValueEntity = $initialValueEntity[0];
        }

        $propertyAccessor = new PropertyAccessor();
        $choiceLabel = $options['initial_value_choice_label'];
        if ($choiceLabel) {
            $label = $propertyAccessor->getValue($initialValueEntity, $choiceLabel);
        } else {
            $label = (string) $initialValueEntity;
        }

        return [
            'label' => $label,
            'code' => $propertyAccessor->getValue($initialValueEntity, 'id'),
        ];
    }

    public function buildQuery($options, FilterQuery $query, $value): void
    {
        if (null == $value) {
            return;
        }

        $propertyPath = explode('.', $options['property']);
        $query->addWhere('id', FilterQuery::OPERATOR_EQUALS, $value, $propertyPath);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'route_parameters' => [],
            'minimum_input_length' => 3,
            'component' => 'filter-auto-complete',
            'model' => 'AutoCompleteEntityFilter',
            'initial_value' => null,
            'initial_value_arguments' => null,
            'initial_value_repository' => null,
            'initial_value_choice_label' => null,
        ]);

        $resolver->setRequired([
            'route',
            'resource',
            'property',
        ]);
    }

    public static function getName(): ?string
    {
        return 'auto_complete_entity';
    }
}
