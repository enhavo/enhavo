<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Filter\Type;

use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextType extends AbstractFilterType
{
    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $operator = $options['operator'];
        if($value !== null && trim($value) !== '') {
            $query->addWhere($property, $operator, $value, $propertyPath);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'operator' => FilterQuery::OPERATOR_LIKE,
            'component' => 'filter-text'
        ]);
    }

    public static function getName(): ?string
    {
        return 'text';
    }
}
