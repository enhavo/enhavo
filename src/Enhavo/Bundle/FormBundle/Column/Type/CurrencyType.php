<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class CurrencyType extends AbstractColumnType
{
    public function __construct(
        private readonly CurrencyFormatter $formatter,
    ) {
    }

    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        $propertyAccessor = new PropertyAccessor();

        $property = $propertyAccessor->getValue($resource, $options['property']);
        $currency = $options['currency'];
        $position = $options['position'];

        $value = $this->formatter->getCurrency($property, $currency, $position);
        $data->set('value', $value);
    }

    public function createColumnViewData(array $options, Data $data): void
    {
        $data->set('currency', $options['currency']);
        $data->set('position', $options['position']);
        $data->set('wrap', $options['wrap']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-text',
            'wrap' => true,
            'currency' => 'Euro',
            'position' => 'right',
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'currency';
    }
}
