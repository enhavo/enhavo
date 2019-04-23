<?php
/**
 * AppController.php
 *
 * @since 08/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractViewController extends AbstractController
{
    /**
     * @var ViewHandlerInterface
     */
    protected $viewHandler;

    /**
     * @var ViewFactory
     */
    protected $viewFactory;

    /**
     * ViewController constructor.
     *
     * @param ViewFactory $viewFactory
     * @param ViewHandlerInterface $viewHandler
     */
    public function __construct(
        ViewFactory $viewFactory,
        ViewHandlerInterface $viewHandler
    ) {
        $this->viewFactory = $viewFactory;
        $this->viewHandler = $viewHandler;
    }
}
