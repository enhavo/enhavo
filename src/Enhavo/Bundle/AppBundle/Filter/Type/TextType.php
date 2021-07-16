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
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextType extends AbstractFilterType
{
    public function buildQuery(FilterQuery $query, $options, $value)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $operator = $options['operator'];
        if($value !== null && trim($value) !== '') {
            $query->addWhere($property, $operator, $value, $propertyPath);
        }
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'operator' => FilterQuery::OPERATOR_LIKE,
            'component' => 'filter-text'
        ]);
    }

    public function getType()
    {
        return 'text';
    }
}
