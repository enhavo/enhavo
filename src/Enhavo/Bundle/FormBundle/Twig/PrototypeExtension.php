<?php

namespace Enhavo\Bundle\FormBundle\Twig;

use Enhavo\Bundle\FormBundle\Prototype\PrototypeManager;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author gseidel
 */
class PrototypeExtension extends AbstractExtension
{
    /** @var PrototypeManager */
    private $prototypeManager;

    /** @var Environment */
    private $environment;

    /** @var bool */
    private $isRendered = false;

    /**
     * PrototypeExtension constructor.
     * @param PrototypeManager $prototypeManager
     */
    public function __construct(PrototypeManager $prototypeManager)
    {
        $this->prototypeManager = $prototypeManager;
    }

    /**
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment): void
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('form_prototype', array($this, 'renderPrototype'), ['is_safe' => ['html']]),
            new TwigFunction('form_prototypes', array($this, 'renderPrototypes'), ['is_safe' => ['html']]),
        );
    }

    public function renderPrototype(FormView $view, $parameters = [])
    {
        /** @var FormRenderer $formRenderer */
        $formRenderer = $this->environment->getRuntime(FormRenderer::class);
        $this->prototypeManager->buildPrototypeView($view);
        return $formRenderer->searchAndRenderBlock($view, 'widget', $parameters);
    }

    public function renderPrototypes()
    {
        if($this->isRendered) {
            return '';
        }

        $this->isRendered = true;

        /** @var FormRenderer $formRenderer */
        return $this->environment->render('@EnhavoForm/admin/form/form/prototypes.html.twig', [
            'prototypes' => $this->prototypeManager->getPrototypeViews()
        ]);
    }
}
