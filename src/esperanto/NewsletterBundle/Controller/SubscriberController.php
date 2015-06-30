<?php

namespace esperanto\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriberController extends Controller
{
    public function activationAction(Request $request)
    {
        $code = $request->get('code');
        $newsletter = $this->get('esperanto_newsletter.repository.subscriber')
            ->findOneBy(array('token' => $code, 'active' => false));

        if(!$newsletter)
        {

        }
        else
        {
            $newsletter->setActive(true);
            $this->getDoctrine()->getManager()->flush();
            $response = $this->render('esperantoNewsletterBundle:Default:email-activation.html.twig');
            return $response;
        }
    }

    public function sendEmailAction(Request $request) {
        $newsletter = $request->get('esperanto_newsletter_newsletter');
        $id = $request->get('newsletterId');
        $title = $newsletter['title'];
        $subject = $newsletter['subject'];
        $text = $newsletter['text'];

        $subscriber = $this->getDoctrine()
            ->getRepository('esperantoNewsletterBundle:Subscriber')
            ->findBy(array('active' => true));

        $container = $this->container;
        $test = $container->getParameter('esperanto_newsletter.subscriber');

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
            ->getRepository('esperantoNewsletterBundle:Newsletter')
            ->findBy(array('id' => $id));

        $currentNewsletter[0]->setSent(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($currentNewsletter[0]);
        $em->flush();

        $response = new Response();
        return $response;
    }
}
