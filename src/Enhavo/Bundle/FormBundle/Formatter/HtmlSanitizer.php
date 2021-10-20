<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-03-16
 * Time: 11:04
 */

namespace Enhavo\Bundle\FormBundle\Formatter;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;

class HtmlSanitizer
{
    /** @var string */
    private $serializationPath;

    /** @var Filesystem */
    private $fs;

    public function __construct(string $serializationPath, Filesystem $fs)
    {
        $this->serializationPath = $serializationPath;
        $this->fs = $fs;
    }

    public function sanitize($value, $options = [])
    {
        if (empty($value)) {
            return $value;
        }

        if (!$this->fs->exists($this->serializationPath)) {
            $this->fs->mkdir($this->serializationPath);
        }

        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', $this->serializationPath);

        foreach ($options as $key => $optionValue) {
            $config->set($key, $optionValue);
        }

        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($value);
    }
}
