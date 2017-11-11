<?php
/**
 * Setting.php
 *
 * @since 30/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Model;

class FilterSetting
{
    /**
     * @var string filter type
     */
    private $type;

    /**
     * @var array
     */
    private $settings = [];

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    public function getSetting($name, $default = null)
    {
        if(isset($this->settings[$name])) {
            return $this->settings[$name];
        }
        return $default;
    }
}