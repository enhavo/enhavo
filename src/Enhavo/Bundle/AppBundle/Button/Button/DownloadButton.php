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
        return $this->renderTemplate('AppBundle:Button:download.html.twig', [
            'type' => $this->getType(),
            'route' => $options['route'],
            'icon' => 'download',
            'display' => true,
            'role' => null,
            'label' => 'app.label.download',
            'translationDomain' => null
        ]);
    }

    public function getType()
    {
        return 'download';
    }
}