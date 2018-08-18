<?php
/**
 * RouteInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Model;

use Symfony\Cmf\Component\Routing\RouteObjectInterface;

interface RouteInterface extends RouteObjectInterface
{
    public function setStaticPrefix($prefix);

    public function getStaticPrefix();

    public function getHost();

    public function setHost($host);

    public function setContent($content);
}