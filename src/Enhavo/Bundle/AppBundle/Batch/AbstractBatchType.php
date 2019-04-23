<?php

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractBatchType extends AbstractType implements BatchTypeInterface
{
    /**
     * @inheritdoc
     */
    abstract function execute(array $options, $resources);

    /**
     * @inheritdoc
     */
    public function createViewData(array $options, $resource = null)
    {
        return [
            'label' => $this->getLabel($options),
            'confirmMessage' => $this->getConfirmMessage($options),
            'position' => $options['position']
        ];
    }

    protected function getLabel($options)
    {
        return $this->container->get('translator')->trans($options['label'], [], $options['translation_domain']);
    }

    protected function getConfirmMessage($options)
    {
        return $this->container->get('translator')->trans($options['confirm_message'], [], $options['translation_domain']);
    }

    /**
     * @inheritdoc
     */
    public function getPermission(array $options)
    {
        return $options['permission'];
    }

    /**
     * @inheritdoc
     */
    public function isHidden(array $options)
    {
        return $options['hidden'];
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'permission'  => null,
            'position'  => 0,
            'translation_domain' => null,
            'hidden' => false,
            'confirm_message' => null
        ]);

        $resolver->setRequired(['label']);
    }
}
