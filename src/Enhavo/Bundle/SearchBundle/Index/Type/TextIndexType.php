<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:29
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Enhavo\Bundle\SearchBundle\Index\IndexData;
use Enhavo\Bundle\SearchBundle\Index\IndexDataBuilder;
use Enhavo\Bundle\SearchBundle\Index\IndexTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class TextIndexType extends AbstractIndexType implements IndexTypeInterface
{
    private PropertyAccessor $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function buildIndex(array $options, $model, IndexDataBuilder $builder): void
    {
        $value = $this->propertyAccessor->getValue($model, $options['property']);

        if ($value) {
            $index = new IndexData(trim($value), $options['weight']);
            $builder->addIndex($index);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('property');

        $resolver->setDefaults([
            'weight' => 1
        ]);
    }

    public static function getName(): ?string
    {
        return 'text';
    }
}
