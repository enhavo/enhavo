<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
