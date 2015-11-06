<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends ResourceController
{
    public function sendEmailAction(Request $request) {
        $newsletter = $request->get('enhavo_newsletter_newsletter');
        $id = $request->get('newsletterId');
        $title = $newsletter['title'];
        $subject = $newsletter['subject'];
        $text = $newsletter['text'];

        $subscriber = $this->getDoctrine()
            ->getRepository('EnhavoNewsletterBundle:Subscriber')
            ->findBy(array('active' => true));

        $container = $this->container;
        $test = $container->getParameter('enhavo_newsletter.subscriber');

        for($i = 0; $i < count($subscriber); $i++)
        {
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setContentType("text/html")
                ->setFrom($test['send_from'])
                ->setTo($subscriber[$i]->getEmail())
                ->setBody($text);
            $this->get('mailer')->send($message);
        }

        $currentNewsletter = $this->getDoctrine()
            ->getRepository('EnhavoNewsletterBundle:Newsletter')
            ->findBy(array('id' => $id));

        $currentNewsletter[0]->setSent(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($currentNewsletter[0]);
        $em->flush();

        $response = new Response();
        return $response;
    }
}
