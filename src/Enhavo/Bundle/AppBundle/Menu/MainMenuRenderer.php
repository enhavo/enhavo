<?php
/**
 * MainMenuRenderer.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class MainMenuRenderer
{
    /**
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * @var string
     */
    protected $template;

    public function __construct($template, EngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
        $this->template = $template;
    }

    public function render($menu)
    {
        return $this->templateEngine->render($this->template, [
            'menus' => $menu
        ]);
    }
}