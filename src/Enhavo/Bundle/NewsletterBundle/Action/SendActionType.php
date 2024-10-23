<?php

namespace Enhavo\Bundle\NewsletterBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendActionType extends AbstractActionType
{
    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        $data->set('resourceId', $resource->getId());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'newsletter.action.send.label',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'icon' => 'send',
            'model' => 'NewsletterSendAction',
        ]);
    }

    public static function getName(): ?string
    {
        return 'newsletter_send';
    }
}
