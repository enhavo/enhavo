<?php
/**
 * StatisticProviderInterface.php
 *
 * @since 17/05/17
 * @author gseidel
 */

namespace Enhavo\Bundle\DashboardBundle\Statistic;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface StatisticProviderInterface extends TypeInterface
{
    public function getLabel();

    public function getNumber();

    public function getIcon();
}