<?php

namespace Enhavo\Bundle\ContactBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\ContactBundle\Contact\ContactManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    use TemplateResolverTrait;

    /**
     * @var ContactManager
     */
    private $contactManager;

    /**
     * ContactController constructor.
     * @param ContactManager $contactManager
     */
    public function __construct(ContactManager $contactManager)
    {
        $this->contactManager = $contactManager;
    }

    public function submitAction(Request $request, $key)
    {
        /** @var FormInterface $form */
        $form = $this->contactManager->createForm($key);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->contactManager->submit($form->getData(), $key);

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'success' => true
                    ]);
                }
                return $this->redirectToRoute('enhavo_contact_finish', ['key' => $key]);
            } else {
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'success' => false
                    ], 400);
                }
            }
        }

        return $this->render($this->contactManager->getTemplate($key, 'submit'), [
            'form' => $form->createView()
        ]);
    }

    public function finishAction($key)
    {
        return $this->render($this->contactManager->getTemplate($key, 'finish'));
    }
}
