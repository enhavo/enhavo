<?php
/**
 * DataProviderInterface.php
 *
 * @since 21/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\DashboardBundle\Chart;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface ChartProviderInterface extends TypeInterface
{
    public function getData($options = []);

    public function getChartType($options = []);

    public function getOptions($options = []);

    public function getApp($options = []);
}
