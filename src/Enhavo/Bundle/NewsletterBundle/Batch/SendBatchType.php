<?php

namespace Enhavo\Bundle\NewsletterBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Exception\SendException;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\Response;
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
     * @return Response|null
     * @throws SendException
     */
    public function execute(array $options, array $resources, ?ResourceInterface $resource = null): ?Response
    {
        foreach($resources as $resource) {
            $this->newsletterManager->prepare($resource);
        }
        return null;
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
