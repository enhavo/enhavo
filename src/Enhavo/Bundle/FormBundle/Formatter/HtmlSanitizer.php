<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Formatter;

use Symfony\Component\Filesystem\Filesystem;

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
        $config->set('Attr.AllowedFrameTargets', ['_blank', '_self']);

        foreach ($options as $key => $optionValue) {
            $config->set($key, $optionValue);
        }

        $purifier = new \HTMLPurifier($config);

        return $purifier->purify($value);
    }
}
