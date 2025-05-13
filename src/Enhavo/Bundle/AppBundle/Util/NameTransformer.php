<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Util;

class NameTransformer
{
    private function normalizeCase($name)
    {
        if (is_array($name)) {
            $parts = $name;
        } elseif (preg_match('/-/', $name)) {
            $parts = explode('-', $name);
        } elseif (preg_match('/_/', $name)) {
            $parts = explode('_', $name);
        } elseif ($this->isCamelCase($name)) {
            $parts = [];
            $name = lcfirst($name);
            $length = strlen($name);
            $word = '';
            for ($i = 0; $i < $length; ++$i) {
                if (ctype_upper($name[$i])) {
                    $parts[] = $word;
                    $word = '';
                    $word .= $name[$i];
                } else {
                    $word .= $name[$i];
                }
            }
            if ($word) {
                $parts[] = $word;
            }
        } else {
            $parts = [$name];
        }

        foreach ($parts as &$part) {
            $part = strtolower($part);
        }

        return $parts;
    }

    private function isCamelCase($name)
    {
        $length = strlen($name);
        for ($i = 0; $i < $length; ++$i) {
            if (ctype_upper($name[$i])) {
                return true;
            }
        }

        return false;
    }

    public function camelCase($name, $firstLetterLow = false)
    {
        $parts = $this->normalizeCase($name);
        $first = true;
        foreach ($parts as &$part) {
            if (!$first || !$firstLetterLow) {
                $part = ucfirst($part);
            }
            $first = false;
        }

        return implode('', $parts);
    }

    public function kebabCase($name)
    {
        $parts = $this->normalizeCase($name);

        return implode('-', $parts);
    }

    public function snakeCase($name)
    {
        $parts = $this->normalizeCase($name);

        return implode('_', $parts);
    }
}
