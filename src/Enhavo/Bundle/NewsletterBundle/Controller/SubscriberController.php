<?php

namespace enhavo\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriberController extends Controller
{
    public function activationAction(Request $request)
    {
        $code = $request->get('code');
        $newsletter = $this->get('enhavo_newsletter.repository.subscriber')
            ->findOneBy(array('token' => $code, 'active' => false));

        if(!$newsletter)
        {

        }
        else
        {
            $newsletter->setActive(true);
            $this->getDoctrine()->getManager()->flush();
            $response = $this->render('enhavoNewsletterBundle:Default:email-activation.html.twig');
            return $response;
        }
    }

    public function sendEmailAction(Request $request) {
        $newsletter = $request->get('enhavo_newsletter_newsletter');
        $id = $request->get('newsletterId');
        $title = $newsletter['title'];
        $subject = $newsletter['subject'];
        $text = $newsletter['text'];

        $subscriber = $this->getDoctrine()
            ->getRepository('enhavoNewsletterBundle:Subscriber')
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
            ->getRepository('enhavoNewsletterBundle:Newsletter')
            ->findBy(array('id' => $id));

        $currentNewsletter[0]->setSent(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($currentNewsletter[0]);
        $em->flush();

        $response = new Response();
        return $response;
    }
}
