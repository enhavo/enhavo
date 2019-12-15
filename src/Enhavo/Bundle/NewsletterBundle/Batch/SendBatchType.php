<?php

namespace Enhavo\Bundle\NewsletterBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * PublishBatch.php
 *
 * @since 04/07/16
 * @author gseidel
 */
class SendBatchType extends AbstractBatchType
{
    /**
     * @param array $options
     * @param Newsletter[] $resources
     */
    public function execute(array $options, $resources)
    {
        $newsletterManager = $this->container->get('enhavo_newsletter.manager');
        foreach($resources as $resource) {
            $newsletterManager->prepare($resource);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'newsletter.batch.action.send',
            'confirm_message' => 'newsletter.batch.message.confirm.send',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'permission' => 'ROLE_ENHAVO_NEWSLETTER_NEWSLETTER_SEND',
        ]);
    }

    public function getType()
    {
        return 'newsletter_send';
    }
}
