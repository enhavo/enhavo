<?php

namespace Enhavo\Bundle\ContactBundle\Twig;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Templating\EngineInterface;

class ContactForm extends \Twig_Extension
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('contact_form', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render($type = 'contact')
    {
        if($this->templateEngine === null) {
            $this->templateEngine = $this->container->get('templating');
        }

        $formFactory = $this->container->get('form.factory');
        $form = $formFactory->create('enhavo_contact_contact');
        $template =  $this->container->getParameter('enhavo_contact.'.$type.'.template.form');

        return $this->templateEngine->render($template, array(
            'form' => $form->createView(),
            'type' => $type
        ));
    }

    public function getName()
    {
        return 'contact_render';
    }
} 