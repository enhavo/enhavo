<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriberController extends ResourceController
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
            $response = $this->render('EnhavoNewsletterBundle:Default:email-activation.html.twig');
            return $response;
        }
    }
}
