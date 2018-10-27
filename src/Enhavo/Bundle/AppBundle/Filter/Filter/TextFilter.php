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

class TextFilter extends AbstractFilter
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
        
        $operator = $options['operator'];
        if($value) {
            $query->addWhere($property, $operator, $value, $joinProperty ? $joinProperty : null);
        }
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'template' => 'EnhavoAppBundle:Filter:text.html.twig',
            'operator' => FilterQuery::OPERATOR_LIKE
        ]);
    }

    public function getType()
    {
        return 'text';
    }
}