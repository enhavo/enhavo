<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\TwoFactor;

use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TwoFactorJsonFormRenderer implements TwoFactorFormRendererInterface
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly VueForm $vueForm,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function renderForm(Request $request, array $templateVars): Response
    {
        $builder = $this->formFactory->createNamedBuilder('', FormType::class, null, [
            'csrf_protection' => false,
        ]);

        $builder->add($templateVars['authCodeParameterName'], TextType::class, [
            'label' => 'two_factor.auth_code',
            'translation_domain' => 'EnhavoUserBundle',
            'mapped' => false,
        ]);

        if ($templateVars['displayTrustedOption']) {
            $builder->add($templateVars['trustedParameterName'], CheckboxType::class, [
                'label' => 'two_factor.trusted',
                'translation_domain' => 'EnhavoUserBundle',
                'required' => false,
                'mapped' => false,
            ]);
        }

        if ($templateVars['isCsrfProtectionEnabled']) {
            $csrfToken = $this->csrfTokenManager->getToken($templateVars['csrfTokenId'])->getValue();
            $builder->add($templateVars['csrfParameterName'], HiddenType::class, [
                'data' => $csrfToken,
                'mapped' => false,
            ]);
        }

        $form = $builder->getForm();

        $error = false;
        if ($templateVars['authenticationError']) {
            $messageKey = $templateVars['authenticationError'];
            $messageData = $templateVars['authenticationErrorData'] ?? [];
            $form->addError(new FormError(
                $this->translator->trans($messageKey, $messageData, 'validators'),
            ));
            $error = true;
        }

        return new JsonResponse([
            'form' => $this->vueForm->createData($form->createView()),
        ], $error  ? Response::HTTP_BAD_REQUEST : Response::HTTP_OK);
    }
}
