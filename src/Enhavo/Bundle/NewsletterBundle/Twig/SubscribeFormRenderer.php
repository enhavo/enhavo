<?php

namespace Enhavo\Bundle\NewsletterBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class SubscribeFormRenderer extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $form;

    /**
     * SubscriberRenderer constructor.
     *
     * @param array $form
     */
    public function __construct(array $form)
    {
        $this->form = $form;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('subscribe_form', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render($template = null)
    {
        $form = $this->getFormFactory()->create($this->form['type']);

        $formTemplate = $this->form['template'];
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