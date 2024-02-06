<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 01.09.18
 * Time: 16:37
 */

namespace Enhavo\Bundle\SearchBundle\Filter\Type;


use Enhavo\Bundle\SearchBundle\Filter\FilterData;
use Enhavo\Bundle\SearchBundle\Filter\FilterDataBuilder;
use Enhavo\Bundle\SearchBundle\Filter\FilterField;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class DateFilterType extends AbstractFilterType
{
    private PropertyAccessor $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function buildFilter(array $options, $model, string $key, FilterDataBuilder $builder): void
    {
        $value = $this->propertyAccessor->getValue($model, $options['property']);
        $data = new FilterData($key, $value instanceof \DateTime ? $value->getTimestamp() : null);
        $builder->addData($data);
    }

    public function buildField(array $options, string $key, FilterDataBuilder $builder): void
    {
        $builder->addField(new FilterField($key, FilterField::FIELD_TYPE_DATE));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'date';
    }
}
