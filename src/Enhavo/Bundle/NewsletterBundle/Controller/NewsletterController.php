<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends ResourceController
{
    public function sendEmailAction(Request $request) {
        $id = $request->get('id');
        $newsletter = $request->get('enhavo_newsletter_newsletter');

        $subscriber = $this->getDoctrine()
            ->getRepository('EnhavoNewsletterBundle:Subscriber')
            ->findBy(array('active' => true));

        $newsletter_config = $this->container->getParameter('enhavo_newsletter.newsletter');

        for($i = 0; $i < count($subscriber); $i++)
        {
            $message = \Swift_Message::newInstance()
                ->setSubject($newsletter['subject'])
                ->setContentType("text/html")
                ->setFrom($newsletter_config['send_from'])
                ->setTo($subscriber[$i]->getEmail())
                ->setBody($newsletter['text']);
            $this->get('mailer')->send($message);
        }

        $currentNewsletter = $this->getDoctrine()
            ->getRepository('EnhavoNewsletterBundle:Newsletter')
            ->find($id);

        $currentNewsletter->setTitle($newsletter['title']);
        $currentNewsletter->setSubject($newsletter['subject']);
        $currentNewsletter->setText($newsletter['text']);
        $currentNewsletter->setSent(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($currentNewsletter);
        $em->flush();

        $response = new Response();
        return $response;
    }
}
