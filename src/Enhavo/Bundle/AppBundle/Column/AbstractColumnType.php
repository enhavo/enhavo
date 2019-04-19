<?php

namespace Enhavo\Bundle\AppBundle\Column;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractColumnType extends AbstractType implements ColumnTypeInterface
{
    public function createResourceViewData(array $options, $resource)
    {
        return null;
    }

    public function createColumnViewData(array $options)
    {
        $data = [
            'label' => $this->getLabel($options),
            'width' => $this->getWidth($options),
            'component' => $options['component'],
            'sortable' => $options['sortable'],
        ];

        return $data;
    }

    /**
     * @inheritdoc
     */
    protected function getLabel($options)
    {
        $translator = $this->container->get('translator');
        return $translator->trans($options['label'], [], $options['translation_domain']);
    }

    /**
     * @inheritdoc
     */
    protected function getWidth($options)
    {
        return $options['width'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => '',
            'translation_domain' => null,
            'width' => 1,
            'sortable' => false
        ]);
    }
}
