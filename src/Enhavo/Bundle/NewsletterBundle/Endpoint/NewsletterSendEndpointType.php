<?php

namespace Enhavo\Bundle\NewsletterBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Repository\NewsletterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsletterSendEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly NewsletterRepository $repository,
        private readonly NewsletterManager $newsletterManager,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $newsletter = $this->repository->find($request->get('id'));

        if ($newsletter === null) {
            throw $this->createNotFoundException();
        }

        if ($newsletter->isPrepared()) {
            $data->set('type', 'error');
            $data->set('message', $this->translator->trans('newsletter.action.send.error.already_sent', [], 'EnhavoNewsletterBundle'));
            $context->setStatusCode(400);
            return;
        }

        $this->newsletterManager->prepare($newsletter);

        $data->set('type', 'success');
        $data->set('message', $this->translator->trans('newsletter.action.send.success', [], 'EnhavoNewsletterBundle'));
    }
}
