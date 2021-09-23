<?php
/**
 * @author blutze-media
 * @since 2021-09-23
 */

namespace Enhavo\Bundle\BlockBundle\Maker;

use Enhavo\Bundle\BlockBundle\Maker\Generator\FormTypeField;

class FormDefinition
{
    /** @var string */
    private $blockPrefix;

    /** @var array */
    private $properties;

    /**
     * @param string $blockPrefix
     * @param array $properties
     */
    public function __construct(string $blockPrefix, array $properties)
    {
        $this->blockPrefix = $blockPrefix;
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return $this->blockPrefix;
    }

    public function getFields(): array
    {
        $fields = [];

        foreach ($this->properties as $key => $config) {
            $fields[] = new FormTypeField($key, $config['form'] ?? []);
        }

        return $field;
    }

}
