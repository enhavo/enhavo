<?php

namespace Enhavo\Bundle\NewsletterBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatch;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;

/**
 * PublishBatch.php
 *
 * @since 04/07/16
 * @author gseidel
 */
class SendBatch extends AbstractBatch
{
    /**
     * @param Newsletter[] $resources
     */
    public function execute($resources)
    {
        $newsletterManager = $this->container->get('enhavo_newsletter.manager');
        foreach($resources as $resource) {
            $newsletterManager->send($resource);
        }
    }

    public function setOptions($parameters)
    {
        parent::setOptions($parameters);

        $this->options = array_merge($this->options, [
            'label' => isset($parameters['label']) ? $parameters['label'] : 'newsletter.batch.action.send',
            'confirmMessage' => isset($parameters['confirmMessage']) ? $parameters['confirmMessage'] : 'newsletter.batch.message.confirm.send',
            'translationDomain' => isset($parameters['translationDomain']) ? $parameters['translationDomain'] : 'EnhavoNewsletterBundle',
            'permission' =>  isset($parameters['permission']) ? $parameters['permission'] : 'ROLE_ADMIN_ENHAVO_NEWSLETTER_NEWSLETTER_SEND',
        ]);
    }

    public function getType()
    {
        return 'newsletter_send';
    }
}