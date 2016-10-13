<?php

namespace Enhavo\Bundle\ContactBundle\Controller;

use Enhavo\Bundle\ContactBundle\Configuration\ConfigurationFactory;
use Enhavo\Bundle\ContactBundle\ErrorResolver\FormErrorResolver;
use Enhavo\Bundle\ContactBundle\Mailer\ContactMailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class ContactController extends Controller
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

    public function __construct(
        TranslatorInterface $translator,
        ContactMailer $contactMailer,
        ConfigurationFactory $configurationFactory,
        FormErrorResolver $formErrorResolver
    ) {
        $this->translator = $translator;
        $this->contactMailer = $contactMailer;
        $this->configurationFactory = $configurationFactory;
        $this->formErrorResolver = $formErrorResolver;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function submitAction(Request $request)
    {
        $name = $request->get('name');

        $configuration = $this->configurationFactory->create($name);

        $form = $this->createForm($configuration->getFormName());
        $form->handleRequest($request);
        if($form->isValid()) {
            $model = $form->getData();
            $this->contactMailer->send($name, $model);

            $response = new JsonResponse(array(
                'message' => $this->translator->trans('contact.form.message.success', [], 'EnhavoContactBundle')
            ));
        } else {
            $errors = $this->formErrorResolver->getErrors($form);
            $response = new JsonResponse(array(
                'message' => $errors[0]
            ), 400);
        }

        return $response;
    }
}