<?php

namespace Enhavo\Bundle\NewsletterBundle\Batch;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterEmailType;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\ResourceBundle\Batch\Type\FormBatchType;
use Enhavo\Bundle\ResourceBundle\Exception\BatchExecutionException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendTestBatchType extends AbstractBatchType
{
    public function __construct(
        private readonly NewsletterManager $newsletterManager,
        private readonly TranslatorInterface $translator,
        private readonly FormFactoryInterface $formFactory
    )
    {
    }

    public function execute(array $options, array $ids, EntityRepository $repository, Data $data, Context $context): void
    {
        $form = $this->formFactory->create(NewsletterEmailType::class);
        $form->handleRequest($context->getRequest());
        if(!$form->isValid()) {
            throw new BatchExecutionException($this->translator->trans('newsletter.batch.error.email', [], 'EnhavoNewsletterBundle'));
        }

        $data = $form->getData();

        foreach ($ids as $id) {
            $resource = $repository->find($id);
            $this->newsletterManager->sendTest($resource, $data['email']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'newsletter.batch.action.send_test',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'permission' => 'ROLE_ENHAVO_NEWSLETTER_NEWSLETTER_SEND_TEST',
            'form_route' => 'enhavo_newsletter_newsletter_test_form',
        ]);
    }

    public static function getParentType(): ?string
    {
        return FormBatchType::class;
    }

    public static function getName(): ?string
    {
        return 'newsletter_send_test';
    }
}
