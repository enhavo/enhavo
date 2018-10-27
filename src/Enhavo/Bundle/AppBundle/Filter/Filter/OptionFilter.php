<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Filter;

use Enhavo\Bundle\AppBundle\Exception\FilterException;
use Enhavo\Bundle\AppBundle\Filter\AbstractFilter;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionFilter extends AbstractFilter
{
    public function render($options, $name)
    {
        return $this->renderTemplate($options['template'], [
            'type' => $this->getType(),
            'label' => $options['label'],
            'translationDomain' => $options['translation_domain'],
            'options' => $options['options'],
            'name' => $name,
        ]);
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if($value == '') {
            return;
        }

        $possibleValues = $options['options'];
        $possibleValues = array_keys($possibleValues);
        $findPossibleValue = false;
        foreach($possibleValues as $possibleValue) {
            if($possibleValue == $value) {
                $findPossibleValue = true;
                break;
            }
        }

        if(!$findPossibleValue) {
            throw new FilterException('Value does not exists in options');
        }

        $property = $options['property'];
        $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'template' => 'EnhavoAppBundle:Filter:option.html.twig'
        ]);
        $optionsResolver->setRequired(['options']);
    }

    public function getType()
    {
        return 'option';
    }
}