<?php

namespace Enhavo\Bundle\NewsletterBundle\Twig;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Templating\EngineInterface;

class NewsletterRender extends \Twig_Extension
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
            new \Twig_SimpleFunction('newsletter_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render($template=null)
    {
        if($this->templateEngine === null) {
            $this->templateEngine = $this->container->get('templating');
        }

        $formFactory = $this->container->get('form.factory');
        if($template == null){
            $form = $formFactory->create('enhavo_newsletter_subscriber');
            $formView = $form->createView();

            $template = 'EnhavoNewsletterBundle:Newsletter:subscriber.html.twig';
        } else {

            $form = $formFactory->create('enhavo_newsletter_subscriber');
            $formView = $form->createView();

            $template = $template;
        }
        return $this->templateEngine->render($template, array(
            'form' => $formView
        ));
    }

    public function getName()
    {
        return 'newsletter_render';
    }
} 