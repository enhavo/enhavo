<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Model;

interface Timestampable
{
    public function getCreatedAt(): ?\DateTime;

    public function setCreatedAt(?\DateTime $date);

    public function getUpdatedAt(): ?\DateTime;

    public function setUpdatedAt(?\DateTime $date);
}
