<?php

namespace Enhavo\Bundle\NewsletterBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Exception\DeliveryException;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Sylius\Component\Resource\Model\ResourceInterface;
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
     * @var NewsletterManager
     */
    private $newsletterManager;

    /**
     * @param array $options
     * @param Newsletter[] $resources
     * @param ResourceInterface|null $resource
     * @throws DeliveryException
     */
    public function execute(array $options, array $resources, ResourceInterface $resource = null)
    {
        foreach($resources as $resource) {
            $this->newsletterManager->prepare($resource);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'newsletter.batch.action.send',
            'confirm_message' => 'newsletter.batch.message.confirm.send',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'permission' => 'ROLE_ENHAVO_NEWSLETTER_NEWSLETTER_SEND',
        ]);
    }

    public static function getName(): ?string
    {
        return 'newsletter_send';
    }
}
