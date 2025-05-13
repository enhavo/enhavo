<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class PhpFunction
{
    public function __construct(
        private string $name,
        private string $visibility,
        private array $args,
        private array $body,
        private ?string $returns,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVisibility(): string
    {
        return $this->visibility;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getBodyString(int $indentation = 8): string
    {
        return implode(sprintf("\n%s", str_repeat(' ', $indentation)), $this->body);
    }

    public function getReturnsString(): string
    {
        return $this->returns ? sprintf(': %s', $this->returns) : '';
    }

    public function getArgumentString()
    {
        $strings = [];
        foreach ($this->args as $key => $value) {
            $strings[] = sprintf('%s $%s', $key, $value);
        }

        return implode(', ', $strings);
    }
}
