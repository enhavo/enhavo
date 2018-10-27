<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Filter;

use Enhavo\Bundle\AppBundle\Filter\AbstractFilter;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanFilter extends AbstractFilter
{
    public function render($options, $name)
    {
        return $this->renderTemplate($options['template'], [
            'type' => $this->getType(),
            'label' => $options['label'],
            'translationDomain' => $options['translation_domain'],
            'name' => $name,
        ]);
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        $property = $options['property'];
        $value = (boolean)$value;
        if($value) {
            $equals = $options['equals'];
            $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $equals);
        }
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'template' => 'EnhavoAppBundle:Filter:boolean.html.twig',
            'equals' => true
        ]);
    }

    public function getType()
    {
        return 'boolean';
    }
}