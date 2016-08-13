<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\OptionAccessor;

class AppViewer extends BaseViewer
{
    public function getBlocks()
    {
        return $this->optionAccessor->get('blocks');
    }

    public function getActions()
    {
        return $this->optionAccessor->get('actions');
    }

    public function getTitle()
    {
        return $this->optionAccessor->get('title');
    }

    public function getType()
    {
        return 'app';
    }

    public function createView()
    {
        $view = parent::createView();
        $view->setTemplate($this->configuration->getTemplate('EnhavoAppBundle:Viewer:app.html.twig'));
        $view->setTemplateData(array_merge($view->getTemplateData(), [
            'blocks' => $this->getBlocks(),
            'actions' => $this->getActions(),
            'title' => $this->getTitle()
        ]));
        return $view;
    }

    public function configureOptions(OptionAccessor $optionsAccessor)
    {
        parent::configureOptions($optionsAccessor);
        $optionsAccessor->setDefaults([
            'title' => '',
            'blocks' => [],
            'actions' => []
        ]);
    }
}