<?php
/**
 * @author blutze-media
 * @since 2021-09-23
 */

/**
 * @author blutze-media
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class FormTypeField
{
    /** @var string */
    private $name;

    /** @var array */
    private $config;

    /**
     * @param string $name
     * @param array $config
     */
    public function __construct(string $name, array $config)
    {
        $this->name = $name;
        $this->config = $config;
    }

    public function getClass(): string
    {
        return $this->config['class'] ?? 'string';
    }

    public function getOptions(): array
    {
        return $this->config['options'] ?? [];
    }

    public function getOptionsString(): string
    {
        return $this->__arrayToString($this->getOptions());
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function __arrayToString(array $array, int $indentation = 16)
    {
        $lines = [];
        $lines[] = '[';

        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $subArray = $this->__arrayToString($item, $indentation+4);
                $lines[] = sprintf("%s'%s' => %s,", str_repeat(' ', $indentation), $key, $subArray);
            } else {
                $lines[] = sprintf("%s'%s' => %s,", str_repeat(' ', $indentation), $key, $item);
            }
        }

        $lines[] = sprintf('%s]', str_repeat(' ', $indentation-4));

        return implode("\n", $lines);
    }
}
