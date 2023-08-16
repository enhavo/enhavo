<?php

namespace Enhavo\Bundle\AppBundle\Model;

interface Timestampable
{
    public function getCreatedAt(): ?\DateTime;
    public function setCreatedAt(?\DateTime $date);

    public function getUpdatedAt(): ?\DateTime;
    public function setUpdatedAt(?\DateTime $date);
}
