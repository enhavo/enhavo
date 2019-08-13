<?php

namespace Enhavo\Bundle\NewsletterBundle\Action;

use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\AppBundle\Action\Type\ModalActionType;

class TestMailActionType extends ModalActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'modal-action',
            'modal' => [
                'type' => 'newsletter-test-modal',
            ],
            'label' => 'newsletter.action.test_mail.label',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'icon' => 'email',
        ]);
    }

    public function getType()
    {
        return 'newsletter_test_mail';
    }
}
