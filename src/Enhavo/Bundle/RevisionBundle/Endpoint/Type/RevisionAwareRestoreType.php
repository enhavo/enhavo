<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RevisionBundle\Endpoint\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\RevisionBundle\Entity\AbstractRevisionAware;
use Enhavo\Bundle\RevisionBundle\Revision\RevisionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class RevisionAwareRestoreType extends AbstractEndpointType
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
        private readonly RevisionManager $revisionManager,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $this->em->getFilters()->disable('revision');

        $repository = $this->resourceManager->getRepository($options['resource']);

        $id = intval($request->get('id'));
        $resource = $repository->find($id);

        if (!$resource instanceof AbstractRevisionAware) {
            throw $this->createNotFoundException();
        }

        $subject = $resource->getSubject();
        if (null === $subject) {
            throw $this->createNotFoundException();
        }

        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('resource_revision', $request->getPayload()->get('token')))) {
            $context->setStatusCode(400);
            $data['success'] = false;
            $data['message'] = 'Invalid token';

            return;
        }

        if ('undelete' === $options['method']) {
            $this->revisionManager->undelete($subject);
        } else {
            $this->revisionManager->dearchive($subject);
        }

        $resource->setSubject(null);
        $this->em->remove($resource);
        $this->em->flush();

        $this->em->getFilters()->enable('revision');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('resource');
        $resolver->setRequired('method');

        $resolver->setAllowedValues('method', ['undelete', 'dearchive']);
    }
}
