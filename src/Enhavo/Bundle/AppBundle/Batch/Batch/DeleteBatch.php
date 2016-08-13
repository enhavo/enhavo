<?php

namespace Enhavo\Bundle\AppBundle\Batch\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatch;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * DeleteBatch.php
 *
 * @since 04/07/16
 * @author gseidel
 */
class DeleteBatch extends AbstractBatch
{
    /**
     * @param ResourceInterface[] $resources
     */
    public function execute($resources)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        foreach($resources as $resource) {
            $em->remove($resource);
        }
        $em->flush();
    }

    public function setOptions($parameters)
    {
        parent::setOptions($parameters);

        $this->options = array_merge($this->options, [
            'label' => isset($parameters['label']) ? $parameters['label'] : 'batch.delete.label',
            'confirmMessage' => isset($parameters['confirmMessage']) ? $parameters['confirmMessage'] : 'batch.delete.message.confirm',
            'translationDomain' => isset($parameters['translationDomain']) ? $parameters['translationDomain'] : 'EnhavoAppBundle',
        ]);
    }

    public function getType()
    {
        return 'delete';
    }
}