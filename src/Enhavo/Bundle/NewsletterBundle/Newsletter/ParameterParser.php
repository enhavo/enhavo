<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Newsletter;

use Enhavo\Bundle\AppBundle\Util\NameTransformer;

class ParameterParser implements ParameterParserInterface
{
    /** @var NameTransformer */
    private $nameTransformer;

    public function __construct()
    {
        $this->nameTransformer = new NameTransformer();
    }

    public function parse(string $content, array $parameters): string
    {
        $values = [];
        $this->getValues($parameters, $values);

        $content = preg_replace_callback('/\$\{([A-Z0-9_.]+)\}/', function ($matches) use ($values) {
            if (array_key_exists($matches[1], $values)) {
                return $values[$matches[1]];
            }

            return '';
        }, $content);

        return $content;
    }

    private function getValues($parameters, array &$values, array $parentKeys = [])
    {
        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $parentKeys[] = $key;
                $this->getValues($value, $values, $parentKeys);
            } else {
                $valueKey = $key;
                if ($parentKeys) {
                    $valueKey = sprintf('%s.%s', join('.', $parentKeys), $key);
                }
                $values[strtoupper($this->nameTransformer->snakeCase($valueKey))] = $value;
            }
        }
    }
}
