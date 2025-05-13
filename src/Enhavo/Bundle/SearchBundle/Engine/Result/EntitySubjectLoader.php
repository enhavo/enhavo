<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Result;

use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;

class EntitySubjectLoader implements SubjectLoaderInterface
{
    private $subject;
    private $loaded = false;

    public function __construct(
        private EntityResolverInterface $entityResolver,
        private string $name,
        private int $id,
    ) {
    }

    public function getSubject(): mixed
    {
        if ($this->loaded) {
            return $this->subject;
        }

        $this->subject = $this->entityResolver->getEntity($this->id, $this->name);
        $this->loaded = true;

        return $this->subject;
    }
}
