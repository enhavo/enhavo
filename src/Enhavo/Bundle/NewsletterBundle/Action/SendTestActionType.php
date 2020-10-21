<?php

namespace Enhavo\Bundle\NewsletterBundle\Action;

use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\AppBundle\Action\Type\ModalActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendTestActionType extends ModalActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'newsletter-send-test',
            'modal' => [
                'component' => 'ajax-form-modal',
                'route' => 'enhavo_newsletter_newsletter_test_form',
                'actionRoute' => 'enhavo_newsletter_newsletter_test'
            ],
            'label' => 'newsletter.action.test_mail.label',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'icon' => 'email',
        ]);
    }

    public function getType()
    {
        return 'newsletter_send_test';
    }
}
