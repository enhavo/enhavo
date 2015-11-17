<?php

namespace Enhavo\Bundle\ContactBundle\Controller;

use Enhavo\Bundle\ContactBundle\Model\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    public function submitAction()
    {
        $contact = new Contact();
        return $this->render('EnhavoContactBundle:Contact:default.html.twig');
    }
}
