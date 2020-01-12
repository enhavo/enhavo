<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Type;

use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgeFilterType extends BetweenFilterType
{
    protected function buildFromQuery(FilterQuery$query, $options, $fromValue)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $date = new \DateTime();
        $date->modify(sprintf('-%s year', intval(($fromValue))));

        $query->addWhere($property, FilterQuery::OPERATOR_LESS_EQUAL, $date->format('Y-m-d'), $propertyPath);
    }

    protected function buildToQuery(FilterQuery $query, $options, $fromTo)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $date = new \DateTime();
        $date->modify(sprintf('-%s year', intval($fromTo)));

        $query->addWhere($property, FilterQuery::OPERATOR_GREATER_EQUAL, $date->format('Y-m-d'), $propertyPath);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'label_from' => 'filter.age.label.from',
            'label_to' => 'filter.age.label.to',
        ]);
    }

    public function getType()
    {
        return 'age';
    }
}
