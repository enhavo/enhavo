<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Component;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Security\Authentication\AuthenticationError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('user_login', template: 'theme/component/user/login.html.twig')]
class LoginComponent
{
    #[ExposeInTemplate]
    public ?string $error = null;

    #[ExposeInTemplate]
    public ?FormView $form = null;

    #[ExposeInTemplate(name: 'failure_path')]
    public ?string $failurePath = null;

    #[ExposeInTemplate(name: 'target_path')]
    public ?string $targetPath = null;

    public function __construct(
        private RequestStack $requestStack,
        private FormFactoryInterface $formFactory,
        private ConfigurationProvider $configurationProvider,
        private AuthenticationError $authenticationError,
    ) {
    }

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'config' => null,
            'error' => null,
            'form' => null,
            'form_options' => [],
            'failure_path' => null,
            'target_path' => null,
        ]);

        return $this->createViewData($resolver->resolve($data));
    }

    private function createViewData(array $options)
    {
        $formClass = $options['form'] ?? $this->configurationProvider->getLoginConfiguration($options['config'])->getFormClass();
        $formOptions = $options['form_options'] ?? $this->configurationProvider->getLoginConfiguration($options['config'])->getFormOptions();

        $request = $this->requestStack->getMainRequest();

        $form = $this->formFactory->create($formClass, null, $formOptions);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
        }

        return [
            'error' => $options['error'] ?? $this->authenticationError->getError(),
            'form' => $form->createView(),
            'failurePath' => $options['failure_path'],
            'targetPath' => $options['target_path'],
        ];
    }

    private function getError()
    {
    }
}
