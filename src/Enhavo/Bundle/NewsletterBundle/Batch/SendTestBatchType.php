<?php

namespace Enhavo\Bundle\NewsletterBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\AppBundle\Batch\Type\FormBatchType;
use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterEmailType;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendTestBatchType extends AbstractBatchType
{
    /** @var NewsletterManager */
    private $newsletterManager;

    /** @var RequestStack */
    private $requestStack;

    /** @var TranslatorInterface */
    private $translator;

    /** @var FormFactoryInterface */
    private $formFactory;

    /**
     * SendTestBatchType constructor.
     * @param NewsletterManager $newsletterManager
     * @param RequestStack $requestStack
     * @param TranslatorInterface $translator
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(NewsletterManager $newsletterManager, RequestStack $requestStack, TranslatorInterface $translator, FormFactoryInterface $formFactory)
    {
        $this->newsletterManager = $newsletterManager;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->formFactory = $formFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute(array $options, array $resources, ResourceInterface $resource = null): ?Response
    {
        $form = $this->formFactory->create(NewsletterEmailType::class);
        $form->handleRequest($this->requestStack->getMasterRequest());
        if(!$form->isValid()) {
            throw new BatchExecutionException($this->translator->trans('newsletter.batch.error.email', [], 'EnhavoNewsletterBundle'));
        }

        $data = $form->getData();

        /** @var NewsletterInterface $resource */
        foreach($resources as $resource) {
            $this->newsletterManager->sendTest($resource, $data['email']);
        }
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
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
