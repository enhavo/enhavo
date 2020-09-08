<?php

namespace Enhavo\Bundle\NewsletterBundle\Twig;

use Enhavo\Bundle\NewsletterBundle\Subscribtion\SubscribtionManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SubscribeFormRenderer extends AbstractExtension
{
    use ContainerAwareTrait;

    /** @var SubscribtionManager */
    private $subscribtionManager;

    /** @var Environment */
    private $twig;

    /**
     * SubscribeFormRenderer constructor.
     * @param SubscribtionManager $subscribtionManager
     * @param Environment $twig
     */
    public function __construct(SubscribtionManager $subscribtionManager, Environment $twig)
    {
        $this->subscribtionManager = $subscribtionManager;
        $this->twig = $twig;
    }


    public function getFunctions()
    {
        return array(
            new TwigFunction('subscribe_form', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param null $subscribtionName
     * @param null $template
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render($subscribtionName = null, $template = null)
    {
        $subscribtion = $this->subscribtionManager->getSubscribtion($subscribtionName);
        $formConfig = $subscribtion->getFormConfig();
        $form = $this->subscribtionManager->createForm($subscribtion, null);

        $formTemplate = $template ?? $formConfig['template'];

        return $this->twig->render($formTemplate, [
            'form' => $form->createView()
        ]);
    }

    public function getName()
    {
        return 'subscribe_form';
    }
}
