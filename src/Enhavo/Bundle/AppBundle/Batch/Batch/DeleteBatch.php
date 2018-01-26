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
            'label' => $this->getOption('label', $parameters,'batch.delete.label'),
            'confirmMessage' => $this->getOption('confirmMessage', $parameters,'batch.delete.message.confirm'),
            'translationDomain' => $this->getOption('translationDomain', $parameters,'EnhavoAppBundle'),
        ]);
    }

    public function getType()
    {
        return 'delete';
    }
}