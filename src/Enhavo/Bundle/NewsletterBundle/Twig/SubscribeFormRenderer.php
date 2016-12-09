<?php

namespace Enhavo\Bundle\NewsletterBundle\Twig;

use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class SubscribeFormRenderer extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $formResolver;

    /**
     * SubscriberRenderer constructor.
     *
     * @param $formResolver Resolver
     */
    public function __construct($formResolver)
    {
        $this->formResolver = $formResolver;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('subscribe_form', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render($formTypeName = null, $template = null)
    {
        if ($formTypeName === null) {
            $formTypeName = 'default';
        }

        $form = $this->getFormFactory()->create($this->formResolver->resolveType($formTypeName));

        $formTemplate = $this->formResolver->resolveTemplate($formTypeName);
        if($template !== null){
            $formTemplate = $template;
        }
        return $this->getTemplateEngine()->render($formTemplate, array(
            'form' => $form->createView()
        ));
    }

    private function getTemplateEngine()
    {
        return $this->container->get('templating');
    }

    private function getFormFactory()
    {
        return $this->container->get('form.factory');
    }

    public function getName()
    {
        return 'subscribe_form';
    }
} 