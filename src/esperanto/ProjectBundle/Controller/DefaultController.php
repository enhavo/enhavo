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

    public function frontendAction()
    {
        $pageRepository = $this->get('esperanto_page.repository.page');
        //$pages = $pageRepository->findAll();

        /** @var $home */
        $home = $pageRepository->findOneBy(array('title' => 'homepage'));
        //$home->getPicture()->getId(); http://masterplan.local/file/1

        /** @var $concept */
        $concept = $pageRepository->findOneBy(array('title' => 'concept'));

        /** @var $about_us */
        $about_us = $pageRepository->findOneBy(array('title' => 'about_us'));

        /** @var $contact */
        $contact = $pageRepository->findOneBy(array('title' => 'contact_details'));

        /** @var $about */
        $about = $pageRepository->findOneBy(array('title' => 'about'));

        //appointment
        $appointmentRepository = $this->get('esperanto_project.repository.appointment');
        $appointments = $appointmentRepository->findAll();

        //download
        $downloadRepository = $this->get('esperanto_project.repository.download');
        $downloads = $downloadRepository->findAll();

        return $this->render('esperantoProjectBundle:Frontend:frontend.html.twig', array(
            'home' => $home,
            'concept' => $concept,
            'about_us' => $about_us,
            'contact' => $contact,
            'about' => $about,
            'appointments' => $appointments,
            'downloads' => $downloads
        ));
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

    public function appointmentMailAction(Request $request)
    {
        $form = $this->get('form.factory')->create(new AppointmentMailType());
        $form->handleRequest($request);
        /** @var $appointmentMail AppointmentMail */
        $appointmentMail = $form->getData();

        $message = \Swift_Message::newInstance()
            ->setSubject("Termin-Teilnahme")
            ->setFrom('info@initiative-masterplan.de')
            ->setTo($this->container->getParameter('contact_form_recipient'))
            ->setBody(sprintf("%s am %s\nEmail: %s\nName: %s\nAlter: %s\nBeruf: %s\n", $appointmentMail->getTitle(), $appointmentMail->getDate(),$appointmentMail->getEmail(), $appointmentMail->getName(), $appointmentMail->getAge(), $appointmentMail->getOccupation()));

        $this->get('mailer')->send($message);

        return $response = new Response();
    }

    public function downloadAction(Request $request) {
        $file = $request->get('file');
        $appPath = $this->container->getParameter('kernel.root_dir');
        $response = new BinaryFileResponse($appPath."/media/".$file);
        $response->headers->set('Content-Type', 'application/octet-stream');
        return $response;
    }
}

