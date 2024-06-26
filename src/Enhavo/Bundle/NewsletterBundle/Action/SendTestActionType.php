<?php

namespace Enhavo\Bundle\NewsletterBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\Type\ModalActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendTestActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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

    public static function getParentType(): ?string
    {
        return ModalActionType::class;
    }


    public static function getName(): ?string
    {
        return 'newsletter_send_test';
    }
}
