<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Filter;

use Enhavo\Bundle\AppBundle\Filter\FilterInterface;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Doctrine\ORM\Query;

class TextFilter extends AbstractType implements FilterInterface
{
    public function render($options, $value)
    {
        return $this->renderTemplate('EnhavoAppBundle:Filter:text.html.twig', [
            'type' => $this->getType(),
            'value' => $value,
            'label' => $this->getOption('label', $options, ''),
            'translationDomain' => $this->getOption('translationDomain', $options, null),
            'icon' => $this->getOption('icon', $options, ''),
            'name' => $this->getRequiredOption('name', $options),
        ]);
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        $property = $this->getRequiredOption('property', $options);
        $joinProperty = null;
        if(substr_count($property, '.') === 1){
            $exploded = explode('.', $property);
            $property = $exploded[0];
            $joinProperty = $exploded[1];
        }
        if($value) {
            $query->addWhere($property, FilterQuery::OPERATOR_LIKE, $value, $joinProperty);
        }
    }

    public function getType()
    {
        return 'text';
    }
}