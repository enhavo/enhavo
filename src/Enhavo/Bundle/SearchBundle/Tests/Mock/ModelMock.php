<?php
/**
 * ModelMock.php
 *
 * @since 23/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SearchBundle\Tests\Mock;

use Enhavo\Bundle\SearchBundle\Attribute\Index;

#[Index('text', ['name' => 'index1', 'property' => 'text2'])]
class ModelMock
{
    #[Index('text')]
    private ?string $text = null;
    private ?string $text2 = null;

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getText2(): ?string
    {
        return $this->text2;
    }

    public function setText2(?string $text2): void
    {
        $this->text2 = $text2;
    }

}
