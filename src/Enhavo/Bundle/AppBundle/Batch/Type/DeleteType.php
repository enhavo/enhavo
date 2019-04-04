<?php

namespace Enhavo\Bundle\AppBundle\Batch\Type;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * DeleteType.php
 *
 * @since 04/07/16
 * @author gseidel
 */
class DeleteType extends AbstractBatchType
{
    /**
     * @inheritdoc
     */
    public function execute(array $options, $resources)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        foreach($resources as $resource) {
            $em->remove($resource);
        }
        $em->flush();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'batch.delete.label',
            'confirm_message' => 'batch.delete.message.confirm',
            'translation_domain' => 'EnhavoAppBundle',
        ]);
    }

    public function getType()
    {
        return 'delete';
    }
}
