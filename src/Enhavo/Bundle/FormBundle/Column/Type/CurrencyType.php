<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\FormBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class CurrencyType extends AbstractColumnType
{
    public function __construct(
        private readonly CurrencyFormatter $formatter
    )
    {
    }

    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
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
            'position' => 'right'
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'currency';
    }
}
