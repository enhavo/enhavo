<?php

namespace Enhavo\Bundle\NewsletterBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendTestType extends AbstractBatchType
{
    /**
     * @param array $options
     * @param Newsletter[] $resources
     */
    public function execute(array $options, $resources)
    {

    }

    /**
     * @inheritdoc
     */
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);
        $data['modal'] = $options['modal'];
        $data['modal_options'] = $options['modal_options'];
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'newsletter.batch.action.send_test',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'permission' => 'ROLE_ENHAVO_NEWSLETTER_NEWSLETTER_SEND',
            'component' => 'batch-modal',
            'modal' => 'newsletter-test-modal',
            'modal_options' => []
        ]);
    }

    public function getType()
    {
        return 'newsletter_send_test';
    }
}
