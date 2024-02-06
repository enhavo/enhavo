<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 25.08.18
 * Time: 21:41
 */

namespace Enhavo\Bundle\SearchBundle\Filter\Type;

use Enhavo\Bundle\SearchBundle\Filter\FilterData;
use Enhavo\Bundle\SearchBundle\Filter\FilterDataBuilder;
use Enhavo\Bundle\SearchBundle\Filter\FilterField;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValueFilterType extends AbstractFilterType
{
    public function buildFilter(array $options, $model, string $key, FilterDataBuilder $builder): void
    {
        $data = new FilterData($key, $options['value']);
        $builder->addData($data);
    }

    public function buildField(array $options, string $key, FilterDataBuilder $builder): void
    {
        $builder->addField(new FilterField($key, $options['field_type']));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('value');
        $resolver->setDefaults(['field_type' => FilterField::FIELD_TYPE_STRING]);
    }

    public static function getName(): ?string
    {
        return 'value';
    }
}
