<?php

namespace Enhavo\Bundle\NewsletterBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Repository\NewsletterRepository;
use Enhavo\Bundle\NewsletterBundle\Repository\ReceiverRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly NewsletterRepository $repository,
        private readonly ReceiverRepository $receiverRepository,
        private readonly NewsletterManager $newsletterManager,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var NewsletterInterface $resource */
        $resource = $options['resource'];

        if ($resource === null) {
            $findValue = $request->get($options['find_by']);
            $resource = $this->repository->findOneBy([
                $options['find_by'] => $findValue,
            ]);
        }

        if ($resource === null) {
            throw $this->createNotFoundException();
        }

        $token = $request->get('token');
        if ($token) {
            $receiver = $this->receiverRepository->findOneBy([
                'token' => $token
            ]);

            if ($receiver instanceof Receiver && $receiver->getNewsletter() === $resource) {
                $content = $this->newsletterManager->render($receiver);
                $context->setResponse(new Response($content));
                return;
            }
        }

        $content = $this->newsletterManager->renderPreview($resource);
        $context->setResponse(new Response($content));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'preview' => false,
            'resource' => null,
            'find_by' => 'id'
        ]);
    }
}
