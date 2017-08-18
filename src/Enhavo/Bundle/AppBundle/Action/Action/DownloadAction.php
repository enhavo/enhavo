<?php

namespace Enhavo\Bundle\AppBundle\Action\Action;

use Enhavo\Bundle\AppBundle\Action\ActionInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class DownloadAction extends AbstractType implements ActionInterface
{
    public function render($parameters)
    {
        $template = $this->getOption('template', $parameters, 'EnhavoAppBundle:Action:default.html.twig');

        return $this->renderTemplate($template, [
            'type' => $this->getType(),
            'actionType' => 'link',
            'route' => $this->getOption('route', $parameters, ''),
            'routeParameters' => $this->getOption('routeParameters', $parameters, []),
            'label' => $this->getOption('label', $parameters, 'label.download'),
            'icon' => $this->getOption('icon', $parameters, 'icon-download'),
            'translationDomain' => $this->getOption('translationDomain', $parameters, 'EnhavoAppBundle'),
            'display' =>  $this->getOption('display', $parameters, true)
        ]);
    }

    public function getType()
    {
        return 'download';
    }
}