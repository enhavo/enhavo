<?php

namespace Enhavo\Bundle\ContactBundle\Twig;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Templating\EngineInterface;

class ContactRender extends \Twig_Extension
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
     * @param $template string
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('contact_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render($template=null)
    {
        if($this->templateEngine === null) {
            $this->templateEngine = $this->container->get('templating');
        }

        $formFactory = $this->container->get('form.factory');
        if($template == null || $template == 'default'){
            $form = $formFactory->create('enhavo_contact_contact');
            $formView = $form->createView();

            $template = $this->container->getParameter('enhavo_contact.contact.template.render');
        } else {

            $form = $formFactory->create('enhavo_contact_contact');
            $formView = $form->createView();

            $template = $template;
        }
        return $this->templateEngine->render($template, array(
            'form' => $formView
        ));
    }

    public function getName()
    {
        return 'contact_render';
    }
} 