<?php

/**
 * LoginWidget.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ThemeBundle\Widget\WidgetInterface;

class LoginWidget extends AbstractType implements WidgetInterface
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'login';
    }

    public function render($options)
    {
        $template = 'EnhavoUserBundle:Theme:Widget/login.html.twig';
        if(isset($options['template'])) {
            $template = $options['template'];
        }

        return $this->renderTemplate($template, [
            'csrf_token' => 'test',
            'last_username' => 'last name'
        ]);
    }
}