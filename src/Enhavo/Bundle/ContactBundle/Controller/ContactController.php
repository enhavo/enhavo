<?php

namespace Enhavo\Bundle\ContactBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\ContactBundle\Configuration\ConfigurationFactory;
use Enhavo\Bundle\ContactBundle\ErrorResolver\FormErrorResolver;
use Enhavo\Bundle\ContactBundle\Mailer\ContactMailer;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class ContactController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var ContactMailer
     */
    protected $contactMailer;

    /**
     * @var ConfigurationFactory
     */
    protected $configurationFactory;

    /**
     * @var FormErrorResolver
     */
    protected $formErrorResolver;

    /**
     * @var RequestConfigurationFactory
     */
    protected $requestConfigurationFactory;

    public function __construct(
        TranslatorInterface $translator,
        ContactMailer $contactMailer,
        ConfigurationFactory $configurationFactory,
        FormErrorResolver $formErrorResolver,
        RequestConfigurationFactory $requestConfigurationFactory
    ) {
        $this->translator = $translator;
        $this->contactMailer = $contactMailer;
        $this->configurationFactory = $configurationFactory;
        $this->formErrorResolver = $formErrorResolver;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function submitAction(Request $request)
    {
        $name = $request->get('name');

        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->createSimple($request);
        $formConfiguration = $this->configurationFactory->create($name);

        $form = $this->createForm($formConfiguration->getForm());
        $form->handleRequest($request);

        $sent = false;
        if($form->isSubmitted()) {
            if($form->isValid()) {
                $model = $form->getData();
                $this->contactMailer->send($name, $model);
                $form = $this->createForm($formConfiguration->getForm());
                $sent = true;

                if($request->isXmlHttpRequest()) {
                    return new JsonResponse(array(
                        'message' => $this->translator->trans('contact.form.message.success', [], 'EnhavoContactBundle')
                    ));
                }
            } else {
                $errors = $this->formErrorResolver->getErrors($form);
                if($request->isXmlHttpRequest()) {
                    return new JsonResponse(array(
                        'message' => $errors[0]
                    ), 400);
                } else {
                    $this->addFlash('error', $errors);
                }
            }
        }

        $redirectRoute = null;
        if($request->get('_redirect_route')) {
            $redirectRoute = ($request->get('_redirect_route'));
        }

        $redirectParameters = [];
        if($request->get('_redirect_route_parameters')) {
            $redirectParameters = json_decode($request->get('_redirect_route_parameters'));
        }

        if($redirectRoute) {
            return $this->redirectToRoute($redirectRoute, $redirectParameters, 302);
        }

        $template = $configuration->getTemplate($formConfiguration->getPageTemplate());
        return $this->render($template, [
            'form' => $form->createView(),
            'name' => $name,
            'sent' => $sent
        ]);
    }
}
