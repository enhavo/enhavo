<?php
/**
 * View.php
 *
 * @since 31/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AdminBundle\Model;


class View
{
    private $parameters = array();

    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    public function getParameter($key)
    {
        if(isset($this->parameters[$key])) {
            return $this->parameters[$key];
        }
        return null;
    }
} 