<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-04-11
 * Time: 21:06
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use FOS\RestBundle\View\View;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class AbstractActionViewer extends AbstractViewer
{
    use ContainerAwareTrait;

    /**
     * @var ActionManager
     */
    protected $actionManager;

    /**
     * ImageCropperViewer constructor.
     * @param ActionManager $actionManager
     */
    public function __construct(ActionManager $actionManager)
    {
        $this->actionManager = $actionManager;
    }

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = parent::createView($options);

        $templateVars = $view->getTemplateData();
        $templateVars['data']['actions'] = $this->actionManager->createActionsViewData($this->createActions($options));
        $templateVars['data']['modals'] = [];
        $view->setTemplateData($templateVars);

        return $view;
    }

    protected function createActions($options)
    {
        return [];
    }
}
