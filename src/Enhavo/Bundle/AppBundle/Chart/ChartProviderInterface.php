<?php
/**
 * DataProviderInterface.php
 *
 * @since 21/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Chart;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface ChartProviderInterface extends TypeInterface
{
    public function getData($options = []);
}