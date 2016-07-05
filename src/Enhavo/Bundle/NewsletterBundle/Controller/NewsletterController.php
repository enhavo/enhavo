<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends ResourceController
{
    public function sendEmailAction(Request $request)
    {
        $id = $request->get('id');
        $newsletterData = $request->get('enhavo_newsletter_newsletter');

        $currentNewsletter = $this->getDoctrine()
            ->getRepository('EnhavoNewsletterBundle:Newsletter')
            ->find($id);

        if (!$currentNewsletter) {
            $currentNewsletter = new Newsletter();
        }
        $currentNewsletter->setTitle($newsletterData['title']);
        $currentNewsletter->setSubject($newsletterData['subject']);
        $currentNewsletter->setText($newsletterData['text']);
        $currentNewsletter->setSent(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($currentNewsletter);
        $em->flush();

        $newsletterManager = $this->get('enhavo_newsletter.manager');
        $newsletterManager->send($currentNewsletter);

        $response = new Response();
        return $response;
    }
}
