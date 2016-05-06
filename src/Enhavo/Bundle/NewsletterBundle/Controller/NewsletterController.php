<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends ResourceController
{
    public function sendEmailAction(Request $request) {
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

        $this->sendNewsletter($currentNewsletter);

        $response = new Response();
        return $response;
    }

    public function batchActionSend($resources)
    {
        $this->isGrantedOr403('edit');
        /** @var Newsletter $newsletter */
        foreach ($resources as $newsletter) {
            $this->sendNewsletter($newsletter);
        }

        return true;
    }

    /**
     * @param Newsletter $newsletter
     */
    protected function sendNewsletter($newsletter)
    {
        $em = $this->getDoctrine()->getManager();

        $subscriber = $this->getDoctrine()
            ->getRepository('EnhavoNewsletterBundle:Subscriber')
            ->findBy(array('active' => true));

        $newsletter_config = $this->container->getParameter('enhavo_newsletter.newsletter');

        for($i = 0; $i < count($subscriber); $i++)
        {
            $message = \Swift_Message::newInstance()
                ->setSubject($newsletter->getSubject())
                ->setContentType("text/html")
                ->setFrom($newsletter_config['send_from'])
                ->setTo($subscriber[$i]->getEmail())
                ->setBody($newsletter->getText());
            $this->get('mailer')->send($message);
        }

        if (!$newsletter->getSent()) {
            $newsletter->setSent(true);

            $em->persist($newsletter);
            $em->flush();
        }
    }
}
