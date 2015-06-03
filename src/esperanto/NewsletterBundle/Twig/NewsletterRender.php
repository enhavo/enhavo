<?php

namespace esperanto\NewsletterBundle\Twig;

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

    public function render($template)
    {
        if($this->templateEngine === null) {
            $this->templateEngine = $this->container->get('templating');
        }

        $formFactory = $this->container->get('form.factory');
        $form = $formFactory->create('esperanto_newsletter_'.$template);
        $formView = $form->createView();

        $template = 'esperantoProjectBundle:Newsletter:'.$template.'.html.twig';

        return $this->templateEngine->render($template, array(
            'form' => $formView
        ));
    }

    public function getName()
    {
        return 'newsletter_render';
    }
} 