<?php
/**
 * @author blutze-media
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class PhpFunction
{
    public function __construct(
        private string  $name,
        private string  $visibility,
        private array   $args,
        private array   $body,
        private ?string $returns,
    )
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    public function getBodyString(int $indentation = 8): string
    {
        return implode(sprintf("\n%s", str_repeat(' ', $indentation)), $this->body);
    }

    /**
     * @return string
     */
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
