<?php

namespace Enhavo\Bundle\NewsletterBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\NewsletterBundle\Factory\NewsletterFactory;
use Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterEmailType;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Repository\NewsletterRepository;
use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsletterTestEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly NewsletterRepository $repository,
        private readonly NewsletterFactory $factory,
        private readonly NewsletterManager $newsletterManager,
        private readonly InputFactory $inputFactory,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        if ($request->get('id')) {
            $newsletter = $this->repository->find($request->get('id'));
            if ($newsletter === null) {
                throw $this->createNotFoundException();
            }
        } else {
            $newsletter = $this->factory->createNew();
        }

        $input = $this->inputFactory->create($options['input']);

        $form = $input->getForm();
        $form->setData($newsletter);

        $submittedFormData = [];
        parse_str($request->get('form'), $submittedFormData);
        $form->submit(isset($submittedFormData[$form->getName()]) ? $submittedFormData[$form->getName()] : []);

        $emailForm = $this->createForm(NewsletterEmailType::class);
        $emailForm->handleRequest($request);

        if (!$emailForm->isValid()) {
            $data->set('type', 'error');
            $data->set('message', $this->translator->trans('newsletter.action.test_mail.invalid', [], 'EnhavoNewsletterBundle'));
            $context->setStatusCode(400);
            return;
        }

        $this->newsletterManager->sendTest($newsletter, $emailForm->getData()['email']);

        $data->set('type', 'success');
        $data->set('message', $this->translator->trans('newsletter.action.test_mail.success', [], 'EnhavoNewsletterBundle'));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'input' => 'enhavo_newsletter.newsletter',
        ]);
    }
}
