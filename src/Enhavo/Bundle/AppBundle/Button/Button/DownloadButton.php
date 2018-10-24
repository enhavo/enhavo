<?php

/**
 * DownloadButton.php
 *
 * @since 05/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Button\Button;

use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class DownloadButton extends AbstractType implements ButtonInterface
{
    public function render($options, $resource)
    {
        return $this->renderTemplate('EnhavoAppBundle:Button:download.html.twig', [
            'type' => $this->getType(),
            'route' => $this->getRequiredOption('route', $options),
            'routeParameters' => $this->getOption('routeParameters', $options, []),
            'icon' => $this->getOption('icon', $options, 'download'),
            'display' => $this->getOption('display', $options, true),
            'role' => $this->getOption('role', $options),
            'label' => $this->getOption('label', $options, 'app.label.download'),
            'translationDomain' => null
        ]);
    }

    public function getType()
    {
        return 'download';
    }
}