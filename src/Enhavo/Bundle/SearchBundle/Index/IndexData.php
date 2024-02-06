<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 19:25
 */

namespace Enhavo\Bundle\SearchBundle\Index;

class IndexData
{
    private ?string $value;
    private ?float $weight;
    private ?string $lang;

    public function __construct(string $value = null, float $weight = null, string $lang = null)
    {
        $this->value = $value;
        $this->weight = $weight;
        $this->lang = $lang;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): void
    {
        $this->weight = $weight;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): void
    {
        $this->lang = $lang;
    }
}
