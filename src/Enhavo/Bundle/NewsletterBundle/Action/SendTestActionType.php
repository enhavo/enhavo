<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterEmailType;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendTestActionType extends AbstractActionType
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly VueForm $vueForm,
    ) {
    }

    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $form = $this->formFactory->create(NewsletterEmailType::class);
        $data->set('form', $this->vueForm->createData($form->createView()));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'newsletter.action.test_mail.label',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'icon' => 'email',
            'model' => 'NewsletterSendTestAction',
        ]);
    }

    public static function getName(): ?string
    {
        return 'newsletter_send_test';
    }
}
