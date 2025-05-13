<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\Registration;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Exception\TokenInvalidException;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationConfirmEndpointType extends AbstractEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly UserManager $userManager,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $token = $request->get('token');
        $configuration = $this->provider->getRegistrationConfirmConfiguration();

        $user = $this->userRepository->findByConfirmationToken($token);

        if (null === $user) {
            throw new TokenInvalidException(sprintf('A user with confirmation token "%s" does not exist', $token));
        }

        $this->userManager->confirm($user, $configuration);
        if ($configuration->isAutoLogin()) {
            $this->userManager->login($user);
        }

        $url = $this->generateUrl($configuration->getRedirectRoute());

        if ('html' === $context->get('_format')) {
            $context->setResponse(new RedirectResponse($url));
        } else {
            $data->set('redirect', $url);
        }
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $configuration = $this->provider->getRegistrationConfirmConfiguration();

        $resolver->setDefaults([
            'template' => $this->resolveTemplate($configuration->getTemplate()),
        ]);
    }
}
