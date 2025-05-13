<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     */
    public function __construct(PrototypeManager $prototypeManager)
    {
        $this->prototypeManager = $prototypeManager;
    }

    public function setEnvironment(Environment $environment): void
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('form_prototype', [$this, 'renderPrototype'], ['is_safe' => ['html']]),
            new TwigFunction('form_prototypes', [$this, 'renderPrototypes'], ['is_safe' => ['html']]),
        ];
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
        if ($this->isRendered) {
            return '';
        }

        $this->isRendered = true;

        /* @var FormRenderer $formRenderer */
        return $this->environment->render('@EnhavoForm/admin/form/form/prototypes.html.twig', [
            'prototypes' => $this->prototypeManager->getPrototypeViews(),
        ]);
    }
}
