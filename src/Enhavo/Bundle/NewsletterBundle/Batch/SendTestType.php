<?php

namespace Enhavo\Bundle\NewsletterBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\AbstractFormBatchType;
use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterEmailType;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
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
        $manager = $this->container->get(NewsletterManager::class);
        $request = $this->container->get('request_stack')->getMasterRequest();
        $translator = $this->container->get('translator');
        $form = $this->container->get('form.factory')->create(NewsletterEmailType::class);

        $form->handleRequest($request);
        if(!$form->isValid()) {
            throw new BatchExecutionException($translator->trans('newsletter.batch.error.email', [], 'EnhavoNewsletterBundle'));
        }

        $data = $form->getData();

        /** @var NewsletterInterface $resource */
        foreach($resources as $resource) {
            $manager->sendTest($resource, $data['email']);
        }
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
