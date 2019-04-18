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
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutoCompleteEntityType extends AbstractFilterType
{
    public function createViewData($options, $name)
    {
        $data = [
            'type' => $this->getType(),
            'key' => $name,
            'value' => null,
            'component' => $options['component'],
            'label' => $this->getLabel($options),
            'url' => $this->getUrl($options),
            'multiple' => false,
            'minimumInputLength' => $options['minimum_input_length'],
        ];
        
        return $data;
    }

    private function getUrl($options)
    {
        $router = $this->container->get('router');
        $url = $router->generate($options['route'], $options['route_parameters']);
        return $url;
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if ($value == '') {
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
            'component' => 'filter-autocomplete-entity'
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
