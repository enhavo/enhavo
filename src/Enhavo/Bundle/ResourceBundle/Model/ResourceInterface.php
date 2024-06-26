<?php

namespace Enhavo\Bundle\ResourceBundle\Model;


if (!class_exists('Sylius\Component\Resource\Model\ResourceInterface')) {
    require_once(__DIR__ . '/SyliusResourceInterface.php');
}

interface ResourceInterface extends \Sylius\Component\Resource\Model\ResourceInterface
{
    public function getId();
}
