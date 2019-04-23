<?php

namespace Enhavo\Bundle\ContentBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * PublishType.php
 *
 * @since 04/07/16
 * @author gseidel
 */
class PublishType extends AbstractBatchType
{
    /**
     * @param array $options
     * @param Publishable[] $resources
     */
    public function execute(array $options, $resources)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        foreach($resources as $resource) {
            $resource->setPublic(true);
        }
        $em->flush();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'batch.publish.label',
            'confirm_message' => 'batch.publish.message.confirm',
            'translation_domain' => 'EnhavoContentBundle',
        ]);
    }

    public function getType()
    {
        return 'publish';
    }
}
