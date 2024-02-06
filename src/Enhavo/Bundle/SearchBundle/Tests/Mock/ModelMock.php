<?php
/**
 * ModelMock.php
 *
 * @since 23/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SearchBundle\Tests\Mock;

use PHPUnit\Framework\TestCase;

class ModelMock
{
    private ?string $text;

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }
}
