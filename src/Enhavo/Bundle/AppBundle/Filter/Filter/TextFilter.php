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
        $template = $this->getOption('template', $options, 'EnhavoAppBundle:Filter:text.html.twig');

        return $this->renderTemplate($template, [
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
        $joinProperty = [];
        if(substr_count($property, '.') >= 1){
            $exploded = explode('.', $property);
            foreach ($exploded as $piece) {
                if(count($exploded) > 1){
                    $joinProperty[] = array_shift($exploded);
                } elseif (count($exploded) === 1) {
                    $property = array_shift($exploded);
                }
            }
        }
        if($value) {
            $query->addWhere($property, FilterQuery::OPERATOR_LIKE, $value, $joinProperty ? $joinProperty : null);
        }
    }

    public function getType()
    {
        return 'text';
    }
}