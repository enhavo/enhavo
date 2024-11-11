<?php

namespace Enhavo\Bundle\RevisionBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Input\Input;
use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Enhavo\Bundle\RevisionBundle\Revision\RevisionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ArchiveEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly RevisionManager $revisionManager,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);

        $resource = $input->getResource();

        if ($resource === null) {
            throw $this->createNotFoundException();
        }

        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('resource_revision', $request->getPayload()->get('token')))) {
            $context->setStatusCode(400);
            $data['success'] = false;
            $data['message'] = 'Invalid token';
            return;
        }

        $this->revisionManager->archive($resource);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('input');
    }

    public static function getName(): ?string
    {
        return 'revision_archive';
    }
}
