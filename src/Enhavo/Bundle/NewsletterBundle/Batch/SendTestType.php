<?php

namespace Enhavo\Bundle\NewsletterBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractFormBatchType;
use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendTestType extends AbstractFormBatchType
{
    /**
     * @param array $options
     * @param Newsletter[] $resources
     * @throws BatchExecutionException
     */
    public function execute(array $options, $resources)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'newsletter.batch.action.send_test',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'permission' => 'ROLE_ENHAVO_NEWSLETTER_NEWSLETTER_SEND_TEST',
            'form_route' => 'enhavo_newsletter_newsletter_test_form',
        ]);
    }

    public function getType()
    {
        return 'newsletter_send_test';
    }
}
