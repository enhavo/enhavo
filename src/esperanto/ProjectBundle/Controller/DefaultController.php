<?php

namespace esperanto\ProjectBundle\Controller;

use esperanto\ProjectBundle\Form\Type\EmailType;
use esperanto\ProjectBundle\Form\Type\AppointmentMailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use esperanto\ProjectBundle\Model\Email;
use esperanto\ProjectBundle\Model\AppointmentMail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('esperantoProjectBundle:Default:index.html.twig');
    }

    public function emailAction(Request $request)
    {
        $form = $this->get('form.factory')->create(new EmailType());
        $form->handleRequest($request);
        /** @var $email Email */
        $email = $form->getData();

        $message = \Swift_Message::newInstance()
            ->setSubject(sprintf('Kontaktformular - %s', $email->getName()))
            ->setFrom('info@initiative-masterplan.de')
            ->setTo($this->container->getParameter('contact_form_recipient'))
            ->setBody(sprintf("Email: %s\nNachricht:\n%s", $email->getEmail(), $email->getMessage()));

        $this->get('mailer')->send($message);

        return $response = new Response();
    }
}

