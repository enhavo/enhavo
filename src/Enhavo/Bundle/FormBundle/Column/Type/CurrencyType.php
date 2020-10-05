<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\FormBundle\Column\Type;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyType extends AbstractColumnType
{

    /**
     * @var CurrencyFormatter
     */
    private $formatter;

    public function __construct(CurrencyFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function createResourceViewData(array $options, $resource)
    {
        $property = $this->getProperty($resource, $options['property']);
        $currency = $options['currency'];
        $position = $options['position'];

        $value = $this->formatter->getCurrency($property, $currency, $position);

        return $value;
    }

    public function createColumnViewData(array $options)
    {
        $data = parent::createColumnViewData($options);

        $data = array_merge($data, [
            'property' => $options['property'],
            'sortingProperty' => ($options['sortingProperty'] ? $options['sortingProperty'] : $options['property']),
            'wrap' => $options['wrap'],
            'currency' => $options['currency'],
            'position' => $options['position']
        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'component' => 'column-text',
            'sortingProperty' => null,
            'wrap' => true,
            'currency' => 'Euro',
            'position' => 'right'
        ]);
        $resolver->setRequired(['property']);
    }

    public function getType()
    {
        return 'currency';
    }
}
