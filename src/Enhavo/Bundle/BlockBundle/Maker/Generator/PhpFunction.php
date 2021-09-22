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

    /** @var string  */
    private $returns = 'void';

    /**
     * @param string $name
     * @param string $visibility
     * @param array $args
     * @param array $body
     * @param string $returns
     */
    public function __construct(string $name, string $visibility, array $args, array $body, string $returns)
    {
        $this->name = $name;
        $this->visibility = $visibility;
        $this->args = $args;
        $this->body = $body;
        $this->returns = $returns;
    }

    public function __toString()
    {
        $string = <<<TXT
    %s function %s(%s): %s
    {
        %s;
    }

TXT;
        return sprintf($string, $this->visibility, $this->name,
            http_build_query($this->args,'',', '),
            $this->returns,
            http_build_query($this->body, '', '    \n'));
    }
}
