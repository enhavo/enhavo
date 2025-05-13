<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Enhavo\Bundle\SearchBundle\Index\IndexDataBuilder;
use Enhavo\Bundle\SearchBundle\Index\IndexDataProvider;
use Enhavo\Bundle\SearchBundle\Index\IndexTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ModelIndexType extends AbstractIndexType implements IndexTypeInterface
{
    private IndexDataProvider $indexDataProvider;
    private PropertyAccessor $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function setIndexDataProvider(IndexDataProvider $indexDataProvider): void
    {
        $this->indexDataProvider = $indexDataProvider;
    }

    public function buildIndex(array $options, $model, IndexDataBuilder $builder): void
    {
        $value = $this->propertyAccessor->getValue($model, $options['property']);

        if (null !== $value) {
            foreach ($this->indexDataProvider->getIndexData($value) as $index) {
                $builder->addIndex($index);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('property');
    }

    public static function getName(): ?string
    {
        return 'model';
    }
}
