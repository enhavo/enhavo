<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 25.08.18
 * Time: 21:42
 */

namespace Enhavo\Bundle\SearchBundle\Filter\Type;

use Enhavo\Bundle\SearchBundle\Filter\FilterData;
use Enhavo\Bundle\SearchBundle\Filter\FilterDataBuilder;
use Enhavo\Bundle\SearchBundle\Filter\FilterField;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class PropertyFilterType extends AbstractFilterType
{
    private PropertyAccessor $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function buildFilter(array $options, $model, string $key, FilterDataBuilder $builder): void
    {
        $value = $this->propertyAccessor->getValue($model, $options['property']);
        $data = new FilterData($key, $value);
        $builder->addData($data);
    }

    public function buildField(array $options, string $key, FilterDataBuilder $builder): void
    {
        $builder->addField(new FilterField($key, $options['field_type']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['property']);
        $resolver->setDefaults(['field_type' => FilterField::FIELD_TYPE_STRING]);
    }

    public static function getName(): ?string
    {
        return 'property';
    }
}
