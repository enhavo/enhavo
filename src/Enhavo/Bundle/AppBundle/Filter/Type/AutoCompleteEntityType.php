<?php
/**
 * AutoCompleteEntityType.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Type;

use Enhavo\Bundle\AppBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutoCompleteEntityType extends AbstractFilterType
{
    public function createViewData($options, $name)
    {
        $data = parent::createViewData($options, $name);

        $initialValue = $this->getInitialValue($options);

        $data = array_merge($data, [
            'choices' => [],
            'initialValue' => $initialValue,
            'value' => $initialValue ? $initialValue['code'] : null,
            'url' => $this->getUrl($options),
            'multiple' => false,
            'minimumInputLength' => $options['minimum_input_length'],
        ]);

        return $data;
    }

    private function getUrl($options)
    {
        $router = $this->container->get('router');
        $url = $router->generate($options['route'], $options['route_parameters']);
        return $url;
    }

    protected function getInitialValue($options)
    {
        if ($options['initial_value'] === null) {
            return null;
        }

        if (!$options['initial_value_repository']) {
            throw new \InvalidArgumentException('If parameter "initial_value" is used, the parameter "initial_value_repository" needs to be set as well');
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

        $choiceLabel = $this->getOption('initial_value_choice_label', $options);
        if ($choiceLabel) {
            $label = $this->getProperty($initialValueEntity, $choiceLabel);
        } else {
            $label = (string)$initialValueEntity;
        }
        return [
            'label' => $label,
            'code' => $this->getProperty($initialValueEntity, 'id')
        ];
    }

    /**
     * @param array $options
     * @return EntityRepository
     */
    private function getRepository($options)
    {
        $repository = null;
        if($this->container->has($options['initial_value_repository'])) {
            $repository = $this->container->get($options['initial_value_repository']);
        } else {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $repository = $em->getRepository($options['initial_value_repository']);
        }

        if(!$repository instanceof EntityRepository) {
            throw new \InvalidArgumentException(sprintf(
                'Parameter "initial_value_repository" should to be type of "%s", but got "%s"',
                EntityRepository::class,
                get_class($repository)
            ));
        }
        return $repository;
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if ($value == null) {
            return;
        }

        $property = $this->getRequiredOption('property', $options);
        $propertyPath = explode('.', $property);
        $query->addWhere('id', FilterQuery::OPERATOR_EQUALS, $value, $propertyPath);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'route_parameters' => [],
            'minimum_input_length' => 3,
            'component' => 'filter-autocomplete-entity',
            'initial_value_arguments' => null,
            'initial_value_repository' => null,
            'initial_value_choice_label' => null
        ]);

        $optionsResolver->setRequired([
            'route'
        ]);
    }

    public function getType()
    {
        return 'auto_complete_entity';
    }
}
