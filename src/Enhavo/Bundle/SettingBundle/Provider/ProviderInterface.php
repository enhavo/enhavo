<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 04/09/16
 * Time: 16:18
 */

namespace Enhavo\Bundle\SettingBundle\Provider;


interface ProviderInterface
{
    public function getSetting($key);
}