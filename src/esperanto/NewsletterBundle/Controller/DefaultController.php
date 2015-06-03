<?php

namespace esperanto\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('esperantoNewsletterBundle:Default:index.html.twig', array('name' => $name));
    }

    public function emailactivationAction(Request $request)
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
}
