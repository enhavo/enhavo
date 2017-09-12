<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 04/09/16
 * Time: 16:18
 */

namespace Enhavo\Bundle\SettingBundle\Provider;

use Enhavo\Bundle\SettingBundle\Exception\ReadOnlyException;

interface ProviderInterface
{
    /**
     * @param $key
     * @return mixed
     */
    public function getSetting($key);

    /**
     * @param $key
     * @return mixed
     */
    public function hasSetting($key);

    /**
     * @param $key
     * @param $value
     * @throws ReadOnlyException
     * @return mixed
     */
    public function setSetting($key, $value);
}