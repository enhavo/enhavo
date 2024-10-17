<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class TextColumnType extends AbstractColumnType
{
    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {
        $propertyAccessor = new PropertyAccessor();

        $value = $propertyAccessor->getValue($resource, $options['property']);

        if ($options['strip_tags']) {
            $value = strip_tags($value);
        }

        $data->set('value', $value);
    }

    public function createColumnViewData(array $options, Data $data): void
    {
        $data->set('wrap', $options['wrap']);
        $data->set('whitespace', $options['whitespace']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-text',
            'model' => 'TextColumn',
            'wrap' => true,
            'strip_tags' => false,
            'whitespace' => 'normal'
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'text';
    }
}
