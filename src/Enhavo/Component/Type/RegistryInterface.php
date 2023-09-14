<?php
/**
 * CollectorInterface.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Component\Type;


interface RegistryInterface
{
    public function getType(string $name): TypeInterface;

    public function getExtensions(TypeInterface $type): array;
}
