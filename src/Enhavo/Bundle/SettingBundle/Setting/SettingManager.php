<?php
/**
 * SettingManager.php
 *
 * @since 06/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SettingBundle\Setting;

use Enhavo\Bundle\SettingBundle\Provider\ProviderInterface;

class SettingManager
{
    /**
     * @var ProviderInterface
     */
    private $databaseProvider;

    /**
     * @var ProviderInterface
     */
    private $parameterProvider;

    public function __construct(ProviderInterface $databaseProvider, ProviderInterface $parameterProvider)
    {
        $this->databaseProvider = $databaseProvider;
        $this->parameterProvider = $parameterProvider;
    }

    public function getSetting($key)
    {
        if($this->databaseProvider->hasSetting($key)) {
            return $this->databaseProvider->getSetting($key);
        }
        if($this->parameterProvider->hasSetting($key)) {
            return $this->parameterProvider->getSetting($key);
        }
        return null;
    }

    public function setSetting($key, $value)
    {
        if($this->databaseProvider->hasSetting($key)) {
            $this->databaseProvider->setSetting($key, $value);
        }
        if($this->parameterProvider->hasSetting($key)) {
            $this->parameterProvider->setSetting($key, $value);
        }
    }
}