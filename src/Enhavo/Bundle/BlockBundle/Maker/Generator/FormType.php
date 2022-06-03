<?php
/**
 * @author blutze-media
 * @since 2021-09-23
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class FormType
{
    public function __construct(
        private string $blockPrefix,
        private array  $properties
    )
    {
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
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
    public function getBlockPrefix(): string
    {
        return $this->blockPrefix;
    }

    public function getFields(): array
    {
        $fields = [];

        foreach ($this->properties as $key => $config) {
            $formConfig = $config['form'] ?? null;
            if ($formConfig) {
                $fields[] = new FormTypeField($key, $formConfig);
            }
        }

        return $fields;
    }

}
