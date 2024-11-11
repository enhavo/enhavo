<?php

namespace Enhavo\Bundle\NewsletterBundle\Batch;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendBatchType extends AbstractBatchType
{
    public function __construct(
        private readonly NewsletterManager $newsletterManager
    )
    {
    }

    public function execute(array $options, array $ids, EntityRepository $repository, Data $data, Context $context): void
    {
        foreach ($ids as $id) {
            $resource = $repository->find($id);
            if ($resource instanceof NewsletterInterface) {
                $this->newsletterManager->prepare($resource);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
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
