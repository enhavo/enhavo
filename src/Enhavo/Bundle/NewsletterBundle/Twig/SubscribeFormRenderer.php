<?php

namespace Enhavo\Bundle\NewsletterBundle\Twig;

use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SubscribeFormRenderer extends AbstractExtension
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
            new TwigFunction('subscribe_form', array($this, 'render'), array('is_safe' => array('html'))),
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
