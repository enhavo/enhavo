<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Grid\Grid;
use Enhavo\Bundle\ResourceBundle\Grid\GridFactory;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\ResourceBundle\Security\CsrfChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ResourceBatchEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
        private readonly GridFactory $gridFactory,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
        private readonly CsrfChecker $csrfChecker,
    ) {
    }

    public static function getName(): ?string
    {
        return 'resource_batch';
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Grid $grid */
        $grid = $this->gridFactory->create($options['grid']);

        $payload = $request->getPayload()->all();

        $type = $payload['type'] ?? null;
        if (!is_string($type)) {
            throw $this->createNotFoundException();
        }

        $batch = $grid->getBatch($type);
        if (null === $batch) {
            throw $this->createNotFoundException();
        }

        if ($this->csrfChecker->isEnabled() && !$this->csrfTokenManager->isTokenValid(new CsrfToken('resource_batch', $request->getPayload()->get('token')))) {
            $context->setStatusCode(400);
            $data['success'] = false;
            $data['message'] = 'Invalid token';

            return;
        }

        $ids = $payload['ids'] ?? null;
        if (!is_array($ids)) {
            $context->setStatusCode(400);
            $data['success'] = false;
            $data['message'] = 'No id\'s selected';

            return;
        }

        $repository = $this->resourceManager->getRepository($grid->getResourceName());
        $batch->execute($ids, $repository, $data, $context);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('grid');
    }
}
