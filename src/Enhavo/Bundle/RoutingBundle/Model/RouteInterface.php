<?php
/**
 * RouteInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Model;

use Symfony\Cmf\Component\Routing\RouteObjectInterface;

interface RouteInterface extends RouteObjectInterface
{
    public function setStaticPrefix(string $prefix);

    public function getStaticPrefix();

    public function getHost();

    public function setHost(?string $pattern);

    public function setContent(object $content);

    public function getCondition();

    public function setCondition(?string $condition);

    public function setName(string $name);

    public function getName();

    public function setOption(string $name, $value);

    public function getOption(string $name);

    public function hasOption(string $name);

    public function getDefaults();

    public function setDefaults(array $defaults);

    public function addDefaults(array $defaults);

    public function getDefault(string $name);

    public function hasDefault(string $name);

    public function setDefault(string $name, $default);
}
