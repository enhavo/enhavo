<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-17
 * Time: 12:27
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

trait UserConfigurationTrait
{
    /**
     * @param Request $request
     * @return UserConfiguration
     */
    private function createConfiguration(Request $request)
    {
        $configuration = new UserConfiguration();

        $config =  $request->attributes->get('_config', []);
        if(!is_array($config)) {
            throw new \InvalidArgumentException('The config has to be an array');
        }

        if(isset($config['template'])) {
            $configuration->setTemplate($config['template']);
        }

        if(isset($config['mail_template'])) {
            $configuration->setMailTemplate($config['mail_template']);
        }

        if(isset($config['route']['confirm'])) {
            $configuration->setConfirmRoute($config['route']['confirm']);
        }

        if(isset($config['route']['confirmed'])) {
            $configuration->setConfirmRoute($config['route']['confirmed']);
        }

        if(isset($config['route']['redirect'])) {
            $configuration->setRedirectRoute($config['route']['redirect']);
        }

        if(isset($config['form'])) {
            $configuration->setConfirmRoute($config['form']);
        }

        return $configuration;
    }
}
