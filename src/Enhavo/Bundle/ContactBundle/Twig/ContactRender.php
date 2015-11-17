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

    public function render($type=null)
    {
        if($this->templateEngine === null) {
            $this->templateEngine = $this->container->get('templating');
        }

        $formFactory = $this->container->get('form.factory');
        if($type == null || $type == 'default'){
            $form = $formFactory->create('enhavo_contact_contact');
            $formView = $form->createView();

            $template = $this->container->getParameter('enhavo_contact.contact.template.render');
        } else {

            $form = $formFactory->create('enhavo_contact_contact');
            $formView = $form->createView();

            $template =  $this->container->getParameter('enhavo_contact.'.$type.'.template.render');
        }
        return $this->templateEngine->render($template, array(
            'form' => $formView,
            'type' => $type
        ));
    }

    public function getName()
    {
        return 'contact_render';
    }
} 