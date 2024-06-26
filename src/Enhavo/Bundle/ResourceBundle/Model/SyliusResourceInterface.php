<?php

namespace Sylius\Component\Resource\Model;


if (!class_exists('Sylius\Component\Resource\Model\ResourceInterface')) {
    return;
}

interface ResourceInterface
{
    public function getId();
}
