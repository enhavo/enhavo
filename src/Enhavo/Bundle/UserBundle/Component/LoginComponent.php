<?php

namespace Enhavo\Bundle\UserBundle\Component;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
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
    ) {
    }

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'config' => null,
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
            'error' => $this->getError(),
            'form' => $form->createView(),
            'failurePath' => $options['failure_path'],
            'targetPath' => $options['target_path'],
        ];
    }

    private function getError()
    {
        $request = $this->requestStack->getMainRequest();
        $session = $request->getSession();
        $authErrorKey = Security::AUTHENTICATION_ERROR;

        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null;
        }
        return $error;
    }
}
