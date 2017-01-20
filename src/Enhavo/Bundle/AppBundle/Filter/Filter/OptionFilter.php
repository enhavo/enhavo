<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Filter;

use Doctrine\ORM\Query;
use Enhavo\Bundle\AppBundle\Exception\FilterException;
use Enhavo\Bundle\AppBundle\Filter\FilterInterface;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class OptionFilter extends AbstractType implements FilterInterface
{
    public function render($options, $value)
    {
        return $this->renderTemplate('EnhavoAppBundle:Filter:option.html.twig', [
            'type' => $this->getType(),
            'value' => $value,
            'label' => $this->getOption('label', $options, ''),
            'translationDomain' => $this->getOption('translationDomain', $options, null),
            'icon' => $this->getOption('icon', $options, ''),
            'options' => $this->getRequiredOption('options', $options),
            'name' => $this->getRequiredOption('name', $options),
        ]);
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if($value == '') {
            return;
        }

        $possibleValues = $this->getRequiredOption('options', $options);
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

        $property = $this->getRequiredOption('property', $options);
        $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value);
    }

    public function getType()
    {
        return 'option';
    }
}