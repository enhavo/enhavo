<?php

namespace Enhavo\Bundle\ContentBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatch;
use Enhavo\Bundle\ContentBundle\Content\Publishable;

/**
 * PublishBatch.php
 *
 * @since 04/07/16
 * @author gseidel
 */
class PublishBatch extends AbstractBatch
{
    /**
     * @param Publishable[] $resources
     */
    public function execute($resources)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        foreach($resources as $resource) {
            $resource->setPublic(true);
        }
        $em->flush();
    }

    public function setOptions($parameters)
    {
        parent::setOptions($parameters);

        $this->options = array_merge($this->options, [
            'label' => isset($parameters['label']) ? $parameters['label'] : 'batch.publish.label',
            'confirmMessage' => isset($parameters['confirmMessage']) ? $parameters['confirmMessage'] : 'batch.publish.message.confirm',
            'translationDomain' => isset($parameters['translationDomain']) ? $parameters['translationDomain'] : 'EnhavoContentBundle',
        ]);
    }

    public function getType()
    {
        return 'publish';
    }
}