<?php
/**
 * @author blutze-media
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class PhpFunction
{
    /** @var string */
    private $name;

    /** @var string */
    private $visibility = 'public';

    /** @var array */
    private $args;

    /** @var array */
    private $body;

    /** @var ?string  */
    private $returns;

    /**
     * @param string $name
     * @param string $visibility
     * @param array $args
     * @param array $body
     * @param ?string $returns
     */
    public function __construct(string $name, string $visibility, array $args, array $body, ?string $returns)
    {
        $this->name = $name;
        $this->visibility = $visibility;
        $this->args = $args;
        $this->body = $body;
        $this->returns = $returns;
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
