<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsletterController extends ResourceController
{
    public function showNewsletterAction(Request $request, $slug)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $contentDocument = $this->get('enhavo_newsletter.repository.newsletter')->findOneBy(array('slug' => $slug));

        if (!$contentDocument) {
            throw new NotFoundHttpException();
        }

        return $this->render($configuration->getTemplate($this->getParameter('enhavo_newsletter.newsletter.template.show')), array(
            'base_template' => $this->getParameter('enhavo_newsletter.newsletter.template.base'),
            'data' => $contentDocument
        ));
    }

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

        $newsletterManager = $this->getNewsletterManager();
        $newsletterManager->send($currentNewsletter);

        return new JsonResponse();
    }

    protected function getNewsletterManager()
    {
        return $this->get('enhavo_newsletter.newsletter.manager');
    }
}
